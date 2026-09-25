# Asaka — commandes de développement. `make help` pour la liste.
COMPOSE := docker compose --env-file .env -f docker/compose.dev.yml

.DEFAULT_GOAL := help
.PHONY: help init up down logs tokens tokens-check wp-setup wp moodle-purge moodle-cli moodle-cron lint reset

help: ## Affiche cette aide
	@grep -E '^[a-z-]+:.*## ' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*## "} {printf "  \033[1m%-14s\033[0m %s\n", $$1, $$2}'

.env:
	cp .env.example .env

init: .env tokens up wp-setup ## Premier lancement : tokens, conteneurs, installation WordPress
	@echo ""
	@echo "  Site public : $$(grep ^WP_URL .env | cut -d= -f2)/formations/supply-chain-humanitaire/"
	@echo "  Campus      : $$(grep ^MOODLE_URL .env | cut -d= -f2)  (admin / voir .env)"
	@echo "  E-mails     : http://localhost:$$(grep ^MAILPIT_PORT .env | cut -d= -f2)"

up: .env ## Démarre l'environnement (Moodle s'installe au premier démarrage)
	$(COMPOSE) up -d --build

down: ## Arrête les conteneurs (les données sont conservées)
	$(COMPOSE) down

logs: ## Suit les journaux (make logs s=moodle)
	$(COMPOSE) logs -f $(s)

tokens: ## Régénère theme.json, tokens et polices depuis design/
	node design/build-tokens.mjs

tokens-check: ## Vérifie que les fichiers générés sont à jour (CI)
	node design/build-tokens.mjs --check

wp-setup: .env ## Installe WordPress, active thème + plugin, crée la fiche de démo
	$(COMPOSE) run --rm wp-cli sh /scripts/wp-setup.sh

wp: ## Commande WP-CLI (make wp c="plugin list")
	$(COMPOSE) run --rm wp-cli wp $(c)

moodle-purge: ## Vide les caches Moodle
	$(COMPOSE) exec -u www-data moodle php admin/cli/purge_caches.php

moodle-cli: ## Script CLI Moodle (make moodle-cli c="cfg.php --name=theme")
	$(COMPOSE) exec -u www-data moodle php admin/cli/$(c)

moodle-cron: ## Lance le cron Moodle une fois
	$(COMPOSE) exec -u www-data moodle php admin/cli/cron.php

lint: tokens-check ## Lint PHP de tout le code Asaka (nécessite php en local)
	find wordpress moodle -name '*.php' -print0 | xargs -0 -n1 php -l > /dev/null

reset: ## Supprime conteneurs ET données (repart de zéro)
	$(COMPOSE) down -v
