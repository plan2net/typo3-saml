MAKE_COMPOSER ?= $(shell if [[ `which ddev` ]]; then echo "ddev composer"; else echo "composer"; fi)
MAKE_COMPOSER_INSTALL_FLAGS ?= --no-interaction --profile
MAKEFLAGS += --warn-undefined-variables
SHELL := bash

#######
# Build
#######

.PHONY: build
build: MAKE_COMPOSER_INSTALL_FLAGS += --no-dev
build: dependencies

.PHONY: dependencies
dependencies:
	${MAKE_COMPOSER} install ${MAKE_COMPOSER_INSTALL_FLAGS}

######
# Test
######

.PHONY: test
test: test-composer-valid test-composer-normalized test-php-cs

.PHONY: test-composer-valid
test-composer-valid:
	${MAKE_COMPOSER} validate

.PHONY: test-composer-normalized
test-composer-normalized: dependencies
	${MAKE_COMPOSER} normalize --dry-run

.PHONY: test-php-cs
test-php-cs:
	${MAKE_COMPOSER} exec -v -- php-cs-fixer fix --dry-run


#####
# Fix
#####

.PHONY: fix
fix: fix-composer fix-php-cs

.PHONY: fix-composer
fix-composer: dependencies
	${MAKE_COMPOSER} normalize

.PHONY: fix-php-cs
fix-php-cs:
	${MAKE_COMPOSER} exec -v -- php-cs-fixer fix


#################
# Run application
#################

.PHONY: start
start: build
	ddev start

.PHONY: stop
stop:
	ddev stop

.PHONY: restart
restart:
	ddev restart
