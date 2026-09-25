#!/usr/bin/env sh
# Installe WordPress, active le thème et le plugin Asaka, crée la fiche formation de démonstration.
# Lancé par `make wp-setup` dans le conteneur wp-cli. Idempotent.
set -eu
cd /var/www/html

until wp db check >/dev/null 2>&1; do echo "[asaka] attente de WordPress…"; sleep 2; done

if ! wp core is-installed 2>/dev/null; then
  wp core install --url="$WP_URL" --title="Asaka Academy" \
    --admin_user="$WP_ADMIN_USER" --admin_password="$WP_ADMIN_PASSWORD" --admin_email="$WP_ADMIN_EMAIL" --skip-email
  wp language core install fr_FR --activate || echo "[asaka] pack fr_FR indisponible, WordPress reste en anglais"
fi

wp theme activate asaka
wp plugin activate asaka-core
wp rewrite structure '/%postname%/' --hard
wp option update blogdescription "Formations certifiantes pour l'humanitaire et le développement"
wp theme delete twentytwentythree twentytwentyfour twentytwentyfive >/dev/null 2>&1 || true
wp plugin delete hello akismet >/dev/null 2>&1 || true

SLUG=supply-chain-humanitaire
if [ -z "$(wp post list --post_type=formation --name=$SLUG --field=ID)" ]; then
  wp post create --post_type=formation --post_status=publish --post_name=$SLUG \
    --post_title="Supply Chain Humanitaire & Gestion des Achats" \
    --post_excerpt="Maîtriser la chaîne d'approvisionnement d'urgence : prévision, appels d'offres, stocks et redevabilité bailleurs." \
    --post_content='<!-- wp:pattern {"slug":"asaka/formation-detail"} /-->'
fi

wp rewrite flush --hard
echo "[asaka] site prêt : $WP_URL/formations/$SLUG/"
