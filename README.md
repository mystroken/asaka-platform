# Asaka Platform

Code du site public (WordPress) et du campus (Moodle 5.2 → 5.3 LTS) d'Asaka Academy.
Le dépôt ne contient que le code Asaka : les cœurs WordPress et Moodle viennent des images Docker.

## Démarrer

Prérequis : Docker (Compose v2), Node 20+, `make`.

```bash
make init
```

Le premier lancement construit l'image Moodle (clone + Composer) puis installe les deux plateformes : comptez 5 à 10 minutes.

| | URL | Accès |
| --- | --- | --- |
| Fiche formation (démo) | http://localhost:8080/formations/supply-chain-humanitaire/ | |
| Catalogue | http://localhost:8080/formations/ | |
| Admin WordPress | http://localhost:8080/wp-admin | `admin` / voir `.env` |
| Campus Moodle | http://localhost:8081 | `admin` / voir `.env` |
| E-mails (Mailpit) | http://localhost:8025 | |

`make help` liste les autres commandes (logs, purge des caches Moodle, WP-CLI, reset).

## Organisation

```
design/            Source de vérité visuelle, synchronisée avec le design system Claude Design
  tokens.json        couleurs, typo, espacements, rayons (format Claude Design)
  components.css     composants asa-* (Header, Footer, Button, EnrolCard, Programme…)
  build-tokens.mjs   génère les fichiers de design des deux thèmes
wordpress/
  themes/asaka/      thème bloc : theme.json, templates, patterns, blocs header/footer
  plugins/asaka-core/ type de contenu « formation », liens campus, future synchro catalogue
moodle/
  theme/asaka/       thème enfant de Boost : tokens → variables Bootstrap 5, SCSS pur
docker/            environnement de dev (WordPress + MariaDB, Moodle + PostgreSQL 16, Mailpit)
  moodle-staging/    image du campus de staging (image Moodle du serveur + thème)
scripts/           installation WordPress (dev)
.github/workflows/ CI (tokens, lint) et déploiement staging
```

## Faire évoluer le design

1. Modifier le design system dans Claude Design, puis reporter `tokens.json` (et `components.css` si un composant change) dans `design/`.
2. `make tokens` : régénère `theme.json`, `tokens.css`, `_tokens.scss`, `_components.scss`, polices et logos.
3. Commiter les fichiers générés : la CI refuse une PR où ils sont périmés.

Côté Moodle, le SCSS est recompilé à chaque requête en dev (`themedesignermode`). Côté WordPress, rechargez la page.

## Règles

- Aucun fichier du cœur WordPress ou Moodle n'est modifié.
- Moodle : SCSS d'abord ; surcharge de template Mustache seulement si le SCSS ne suffit pas (budget : 15) ; renderer PHP en dernier recours.
- WordPress : blocs natifs et patterns pour le contenu éditable ; blocs dynamiques (PHP, sans build) pour ce qui dépend de données.
- Toute chaîne visible est traduisible (`__()` côté WordPress, fichiers `lang/` côté Moodle).

## Déploiement staging

Staging sur le VPS serenihost : campus https://app.masaka-academy.org, site public https://marketing.masaka-academy.org.

Chaque push sur `main` qui passe la CI déclenche `deploy-staging.yml`. GitHub transmet seulement le commit, en SSH, avec une clé restreinte à `/srv/bin/deploy-asaka` (dépôt serenihost-server). Le serveur récupère alors le code et :

- reconstruit l'image `asaka-moodle:staging` (image Moodle du serveur + `moodle/theme/asaka`, voir `docker/moodle-staging/Dockerfile`) si le thème a changé, puis relance Moodle et lance l'upgrade ;
- copie le thème et le plugin WordPress dans le conteneur `asaka-marketing`.

Premier déploiement : *Actions → Deploy staging → Run workflow* avec « Réglages initiaux » (thème Moodle, URL du campus, permaliens).
Secrets GitHub (environnement `staging`) : `STAGING_SSH_HOST`, `STAGING_SSH_USER`, `STAGING_SSH_KEY`, `STAGING_KNOWN_HOSTS`.
Mise en place et retour arrière : `docs/RUNBOOK.md` du dépôt serenihost-server, section « Asaka (staging) ».
