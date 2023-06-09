# leave empty to disable
# -v - verbose;
# -vv - more details
# -vvv - enable connection debugging
DEBUG_VERBOSITY ?=

DOCKER_CMD =

COMPOSER_RUN = $(DOCKER_CMD) composer

# https://phpstan.org/user-guide/output-format
export PHPSTAN_OUTPUT_FORMAT ?= table

# Self documenting Makefile code
# ------------------------------------------------------------------------------------
ifneq ($(TERM),)
	BLACK := $(shell tput setaf 0)
	RED := $(shell tput setaf 1)
	GREEN := $(shell tput setaf 2)
	YELLOW := $(shell tput setaf 3)
	LIGHTPURPLE := $(shell tput setaf 4)
	PURPLE := $(shell tput setaf 5)
	BLUE := $(shell tput setaf 6)
	WHITE := $(shell tput setaf 7)
	RST := $(shell tput sgr0)
else
	BLACK := ""
	RED := ""
	GREEN := ""
	YELLOW := ""
	LIGHTPURPLE := ""
	PURPLE := ""
	BLUE := ""
	WHITE := ""
	RST := ""
endif
MAKE_LOGFILE = /tmpwayofdev-laravel-package-tpl.log
MAKE_CMD_COLOR := $(BLUE)

default: all

help:
	@echo 'Management commands for package:'
	@echo 'Usage:'
	@echo '    ${MAKE_CMD_COLOR}make${RST}                       Setups dependencies for fresh-project, like composer install, git hooks and others...'
	@grep -E '^[a-zA-Z_0-9%-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "    ${MAKE_CMD_COLOR}make %-21s${RST} %s\n", $$1, $$2}'
	@echo
	@echo '    📑 Logs are stored in      $(MAKE_LOGFILE)'
	@echo
	@echo '    📦 Package                 laravel-package-tpl (github.com/wayofdev/laravel-package-tpl)'
	@echo '    🤠 Author                  Andrij Orlenko (github.com/lotyp)'
	@echo '    🏢 ${YELLOW}Org                     wayofdev (github.com/wayofdev)${RST}'
.PHONY: help

.EXPORT_ALL_VARIABLES:


# Default action
# Defines default command when `make` is executed without additional parameters
# ------------------------------------------------------------------------------------
all: install hooks
.PHONY: all


# System Actions
# ------------------------------------------------------------------------------------
prepare:
	mkdir -p .build/php-cs-fixer
.PHONY: prepare


# Composer
# ------------------------------------------------------------------------------------
install: ## Installs composer dependencies
	$(COMPOSER_RUN) install
.PHONY: install

update: ## Updates composer dependencies by running composer update command
	$(COMPOSER_RUN) update
.PHONY: update


# Code Quality, Git, Linting, Testing
# ------------------------------------------------------------------------------------
hooks: ## Install git hooks from pre-commit-config
	pre-commit install
	pre-commit autoupdate
.PHONY: hooks

lint-yaml: ## Lints yaml files inside project
	yamllint .
.PHONY: lint-yaml

lint-php: prepare ## Fixes code to follow coding standards using php-cs-fixer
	$(COMPOSER_RUN) cs:fix
.PHONY: lint-php

lint-diff: prepare ## Runs php-cs-fixer in dry-run mode and shows diff which will by applied
	$(COMPOSER_RUN) cs:diff
.PHONY: lint-diff

lint-stan: ## Runs phpstan – static analysis tool
	$(COMPOSER_RUN) stan
.PHONY: lint-stan

lint-stan-ci:
	$(COMPOSER_RUN) stan:ci
.PHONY: lint-stan-ci

test: ## Run project php-unit and pest tests
	$(COMPOSER_RUN) test
.PHONY: test

test-cc: ## Run project php-unit and pest tests in coverage mode and build report
	$(COMPOSER_RUN) test:cc
.PHONY: test-cc
