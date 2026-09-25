# Asaka Platform — contexte pour Claude Code

Contexte repris d'une conversation claude.ai (25 septembre 2026). Répondre en français.
Le développeur (Ken) préfère des fichiers complets et directement utilisables plutôt que des extraits.

## Le projet

Asaka Academy (anciennement « MASAKA » : ne plus utiliser ce nom) forme des professionnels de l'humanitaire
et du développement en Afrique francophone. Deux plateformes qui doivent se lire comme un seul produit :

- **Site public** : WordPress, thème bloc `wordpress/themes/asaka`, plugin `wordpress/plugins/asaka-core`.
- **Campus** : Moodle **5.2**, puis montée en **5.3 LTS** dès sa sortie (5 octobre 2026). Thème `moodle/theme/asaka`, enfant de Boost.

Sources métier (hors dépôt) : cahier des charges v2.3 (.docx) et fichier de suivi v2.2 (.xlsx).
Le cahier prévoyait Moodle 4.5 ; le passage direct en 5.x doit être tracé comme décision D-11 dans le registre.

## Design

- Design system dans Claude Design : https://claude.ai/artifact/7Lf4itUTTcb7zEXuHPDbUK
  (tokens, General Sans, logos, composants Header / Footer / Button / Badge / Field / EnrolCard / Progress / Programme).
- Prototype HiFi d'origine (parcours apprenant en 8 écrans, 1440 px) : https://claude.ai/artifact/PAPeB25Q49Wgf5eWgQvBP4
  Écrans : 1 fiche formation · 2 paiement · 3 confirmation · 4 page du cours · 5 leçon · 6 quiz · 7 fin de parcours · 8 certificat.
- Prix du prototype : 45 000 FCFA / 69 € / 75 $.
- Les maquettes du site public (accueil, catalogue, à propos…), le mobile et les écrans formateur/admin n'existent pas encore.
  Header mobile et Footer ont été dessinés dans le design system.

`design/tokens.json` a exactement le format du design system Claude Design.
`make tokens` régénère theme.json, tokens.css, _tokens.scss, _components.scss, polices et logos. Les fichiers
générés sont commités ; la CI (`--check`) refuse une PR où ils sont périmés. Ne jamais les éditer à la main.

Limites de contraste connues : `border` #DCDDE0 (1.4:1, donc label visible obligatoire sur chaque champ),
`accent-strong` #B26A12 (4.2:1, texte ≥ 19 px gras uniquement). Texte `ink` sur `accent`, jamais blanc.

## Méthodologie retenue

- Monorepo, sans le cœur des CMS (fournis par les images Docker). Aucune modification du cœur.
- Ordre de travail : par parcours (§7.1 du cahier), pas par plateforme ; démo à chaque comité projet sur le staging.
- Moodle, règle d'escalade par écran : 1) SCSS seul → 2) surcharge Mustache (budget ~15) → 3) renderer PHP → jamais le cœur.
  Pas de format de cours sur mesure au MVP. Écrans 2, 3 et 7 = plugins, pas thème (paiement : `paygw_*` sur le sous-système paiement).
- WordPress : blocs natifs et patterns pour le contenu éditable ; blocs dynamiques PHP (`render.php`, sans build) pour ce qui dépend de données.
  Le catalogue sera synchronisé depuis Moodle (web services → CPT `formation` via WP-Cron, méta `asaka_moodle_course_id`).
- Chaînes traduisibles FR/EN dès le départ.
- Définition de « terminé » pour un écran : conforme à la maquette sur 3 breakpoints, clavier et contrastes vérifiés,
  Lighthouse mobile ≥ 80 côté WordPress.

## Faits techniques vérifiés sur Moodle 5.2 (MOODLE_502_STABLE)

- Racine web = `public/` ; `config.php` et `admin/cli/` restent à la racine du code (pas sous public/). Plugins sous `public/theme/…`.
- PHP 8.3 minimum, PostgreSQL 16, `composer install` obligatoire ; routeur : `FallbackResource /r.php`.
- Bootstrap 5 : surcharger les boutons via les variables `--bs-btn-*` (voir scss/post.scss).
- Preset Boost : variables `!default`, donc `pre.scss` les écrase ; layouts hérités du parent ; callbacks pre/extra du parent puis de l'enfant.
- `get_logo_url` / `get_compact_logo_url` non typées, donc surchargeables ; polices via `[[font:theme|fichier.woff2]]`.
- Boost 5.2 a une page de connexion avec image générique à gauche, masquée dans post.scss.
- WordPress ne supporte pas PostgreSQL : il tourne sur MariaDB.

## État actuel

Commit initial : environnement Docker (`make init`), thème bloc WordPress, thème Moodle en SCSS pur (aucune surcharge de template),
CI (tokens, lint PHP, JSON), déploiement staging (rsync + `scripts/deploy-staging-post.sh` après CI verte).

Validé hors Docker : lint PHP, JSON/YAML valides, SCSS complet compilé avec Dart Sass.
Premier `make init` fait le 25/09/2026 : installation neuve et redémarrage OK (Moodle 5.2.3+, build 20260916),
SCSS du thème compilé par scssphp sans erreur, fiche formation WordPress rendue sans notice PHP.
Page de connexion Moodle : Boost 5.2 affiche « Welcome to Moodle » + statistiques si `$CFG->auth_instructions` est vide ;
l'entrypoint le renseigne à l'installation (à faire aussi sur le staging).

Encore à vérifier (nécessite une session connectée) :
- Contraste des éléments de la navbar Moodle sombre (notifications, bascule du mode édition, menu utilisateur).
- Blocs signalés « invalides » dans l'éditeur WordPress sur la fiche formation (le rendu public n'est pas touché).

## Prochaines étapes

1. Vérifier connecté : navbar Moodle, blocs de la fiche formation dans l'éditeur WordPress.
2. Écran 4 (page du cours Moodle) : hero ink + carte de progression, index de cours ; décider des onglets Contenu/Devoirs/Ressources/Échanges (non natifs).
3. Écrans 5 et 6 (leçon, quiz) en SCSS ; écran 8 avec `tool_certificate`.
4. `mobile.css` pour l'app Moodle officielle (réglage `mobilecssurl`).
5. Plus tard : plugin de paiement, synchro catalogue WordPress ← Moodle, `moodle-plugin-ci` dans la CI.

Décisions encore ouvertes (readiness gate) : D-02 multi-devises, D-06 langues (sélecteur FR/EN du footer non branché),
D-07 certificat, D-08 hébergement, D-11 Moodle 5.x à faire valider.

## Staging

VPS Docker (nginx-proxy/acme). Secrets GitHub, environnement `staging` :
STAGING_SSH_HOST, STAGING_SSH_USER, STAGING_SSH_KEY, STAGING_KNOWN_HOSTS, STAGING_BASE_DIR.
L'utilisateur de déploiement doit être dédié, avec une clé restreinte (`command=` dans authorized_keys).
