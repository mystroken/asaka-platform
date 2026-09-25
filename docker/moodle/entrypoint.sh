#!/usr/bin/env bash
# Installe Moodle au premier démarrage (base vide), sinon applique les mises à jour de plugins.
set -euo pipefail

cd /var/www/moodle
export PGPASSWORD="${MOODLE_DB_PASSWORD}"
PSQL=(psql -h "${MOODLE_DB_HOST}" -U "${MOODLE_DB_USER}" -d "${MOODLE_DB_NAME}" -tAc)
as_www() { runuser -u www-data -- "$@"; }

until "${PSQL[@]}" 'select 1' >/dev/null 2>&1; do
  echo "[asaka] attente de PostgreSQL…"; sleep 2
done

chown -R www-data:www-data /var/www/moodledata

if [ "$("${PSQL[@]}" "select to_regclass('mdl_config') is not null")" != "t" ]; then
  echo "[asaka] installation de Moodle (quelques minutes)…"
  as_www php admin/cli/install_database.php \
    --agree-license --lang=en \
    --fullname="Asaka Academy" --shortname="Asaka" \
    --summary="Campus Asaka Academy (développement)" \
    --adminuser="${MOODLE_ADMIN_USER}" --adminpass="${MOODLE_ADMIN_PASSWORD}" \
    --adminemail="${MOODLE_ADMIN_EMAIL}" --supportemail="${MOODLE_ADMIN_EMAIL}"
  # Le thème est imposé par $CFG->theme dans config.php : cfg.php refuserait de le modifier.
  as_www php admin/cli/cfg.php --name=registerauth --set=email
  # Panneau gauche de la page de connexion : sans ce texte, Boost affiche « Welcome to Moodle » et ses statistiques.
  as_www php admin/cli/cfg.php --name=auth_instructions \
    --set="<h1 class=\"h2 mb-3\">Bienvenue sur le campus Asaka</h1><p>Formations certifiantes pour les professionnels de l'humanitaire et du développement en Afrique francophone.</p>"
else
  as_www php admin/cli/upgrade.php --non-interactive
fi

as_www php admin/cli/purge_caches.php
echo "[asaka] campus prêt sur ${MOODLE_URL}"
exec "$@"
