#!/usr/bin/env bash
# Exécuté sur le VPS de staging après la synchronisation des fichiers (voir deploy-staging.yml).
set -euo pipefail

BASE="${BASE:-/srv/asaka-staging}"
COMPOSE="docker compose -f $BASE/compose.yml"
MOODLE_SVC="${MOODLE_SVC:-moodle}"
WP_SVC="${WP_SVC:-wordpress}"
# Moodle 5.1+ : les scripts CLI restent à la racine du code (admin/cli), pas sous public/.
MOODLE_CLI="${MOODLE_CLI:-/var/www/moodle/admin/cli}"

echo "→ Moodle : upgrade (sans effet si aucun version.php n'a changé)"
$COMPOSE exec -T -u www-data "$MOODLE_SVC" php "$MOODLE_CLI/upgrade.php" --non-interactive

echo "→ Moodle : purge des caches (recompile le SCSS du thème)"
$COMPOSE exec -T -u www-data "$MOODLE_SVC" php "$MOODLE_CLI/purge_caches.php"

echo "→ Reset opcache (reload gracieux d'Apache/PHP-FPM)"
$COMPOSE exec -T "$MOODLE_SVC" kill -USR1 1 2>/dev/null || $COMPOSE restart "$MOODLE_SVC"
$COMPOSE exec -T "$WP_SVC" kill -USR1 1 2>/dev/null || $COMPOSE restart "$WP_SVC"

echo "✓ Staging à jour"
