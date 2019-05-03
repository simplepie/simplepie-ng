#-------------------------------------------------------------------------------
# Global variables.

PHP_LAST=7.2
PHP_CURR=7.3
PHP_NEXT=7.4

BUILD_DOCKER=docker build --compress --force-rm --squash
BUILD_COMPOSE=docker-compose build --pull --compress --parallel

COMPOSE_72=tests-72 benchmarks-72 coverage-72
COMPOSE_73=tests-73 benchmarks-73 coverage-73

TEST_QUICK=tests-72 tests-73
TEST_COVER=coverage-72 coverage-73
TEST_BENCH=benchmarks-72 benchmarks-73

IMAGES_72=simplepieng/base:$(PHP_LAST) simplepieng/benchmarks:$(PHP_LAST) simplepieng/test-coverage:$(PHP_LAST) simplepieng/test-runner:$(PHP_LAST)
IMAGES_73=simplepieng/base:$(PHP_CURR) simplepieng/benchmarks:$(PHP_CURR) simplepieng/test-coverage:$(PHP_CURR) simplepieng/test-runner:$(PHP_CURR)

#-------------------------------------------------------------------------------
# Running `make` will show the list of subcommands that will run.

all:
	@cat Makefile | grep "^[a-z]" | sed 's/://' | awk '{print $$1}'

#-------------------------------------------------------------------------------
# Base Docker images so that we have some repeatability

.PHONY: base-72
base-72:
	$(BUILD_DOCKER) --tag simplepieng/base:$(PHP_LAST) --file build/base/Dockerfile-$(PHP_LAST) .

.PHONY: base-73
base-73:
	$(BUILD_DOCKER) --tag simplepieng/base:$(PHP_CURR) --file build/base/Dockerfile-$(PHP_CURR) .

.PHONY: base-all
base-all: base-72 base-73

#-------------------------------------------------------------------------------
# Build all development focused containers.

.PHONY: build-all
build-all:
	$(BUILD_COMPOSE)

.PHONY: build-72
build-72:
	$(BUILD_COMPOSE) $(COMPOSE_72)

.PHONY: build-73
build-73:
	$(BUILD_COMPOSE) $(COMPOSE_73)

.PHONY: build-test
build-test:
	$(BUILD_COMPOSE) tests-72 tests-73

.PHONY: build-coverage
build-coverage:
	$(BUILD_COMPOSE) coverage-72 coverage-73

.PHONY: build-benchmarks
build-benchmarks:
	$(BUILD_COMPOSE) benchmarks-72 benchmarks-73

#-------------------------------------------------------------------------------
# Clean Docker containers

.PHONY: dockerfile
dockerfile:
	find $$(pwd)/build -type f -name Dockerfile-$(PHP_LAST)* -not -path "*build/base*" | xargs -P $$(nproc) -I% bash -c 'sed -i -r "s/^FROM simplepieng\/base:([^\n]+)/FROM simplepieng\/base:$(PHP_LAST)/" %'
	find $$(pwd)/build -type f -name Dockerfile-$(PHP_LAST)* -not -path "*build/base*" | xargs -P $$(nproc) -I% bash -c 'cp -fv % $$(echo % | sed -r "s/$(PHP_LAST)/$(PHP_CURR)/g")'
	find $$(pwd)/build -type f -name Dockerfile-$(PHP_CURR)* -not -path "*build/base*" | xargs -P $$(nproc) -I% bash -c 'sed -i -r "s/^FROM simplepieng\/base:$(PHP_LAST)/FROM simplepieng\/base:$(PHP_CURR)/" %'

.PHONY: push-images
push-images:
	docker push simplepieng/base:$(PHP_LAST)
	docker push simplepieng/base:$(PHP_CURR)

.PHONY: clean-72
clean-72:
	docker image rm --force $(IMAGES_72)

.PHONY: clean-73
clean-73:
	docker image rm --force $(IMAGES_73)

.PHONY: clean-all
clean-all: clean-72 clean-73

.PHONY: rmint
rmint:
	# Remove the intermediate Docker containers. All Docker image builds will start over from scratch.
	docker images | grep "<none>" | awk '{print $$3}' | xargs -P 2 -I% docker rmi -f %

#-------------------------------------------------------------------------------
# Running tests

.PHONY: test
test:
	bin/phpunit --testsuite all

.PHONY: test-quick
test-quick:
	docker-compose up $(TEST_QUICK)

.PHONY: test-coverage
test-coverage:
	docker-compose up $(TEST_COVER)

.PHONY: test-benchmark
test-benchmark:
	docker-compose up $(TEST_BENCH)

#-------------------------------------------------------------------------------
# PHP build process stuff

.PHONY: install-composer
install-composer:
	- SUDO="" && [[ $$UID -ne 0 ]] && SUDO="sudo"; \
	curl -sSL https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer \
	    | $$SUDO $$(which php) -- --install-dir=/usr/local/bin --filename=composer

.PHONY: install
install:
	composer self-update
	composer install -oa

.PHONY: dump
dump:
	composer dump-autoload -oa

.PHONY: install-hooks
install-hooks:
	printf '#!/usr/bin/env bash\nmake lint\nmake test' > .git/hooks/pre-commit
	chmod +x .git/hooks/pre-commit

#-------------------------------------------------------------------------------
# Extra Resources

.PHONY: entities
entities:
	wget -O resources/entities.json https://www.w3.org/TR/html5/entities.json
	tools/entities.php
	cat resources/entities.dtd | uniq > resources/entities2.dtd
	mv resources/entities2.dtd resources/entities.dtd

#-------------------------------------------------------------------------------
# Documentation tasks

.PHONY: docs
docs:
	# composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader --ignore-platform-reqs
	# git reset --hard HEAD
	sami update --force docs/sami-config.php

.PHONY: push
push:
	rm -Rf /tmp/gh-pages
	git clone git@github.com:simplepie/simplepie-ng.git --branch gh-pages --single-branch /tmp/gh-pages
	rm -Rf /tmp/gh-pages/*
	cp -Rf ./docs/_build/* /tmp/gh-pages/
	cp -Rf ./docs/redirect.tmpl /tmp/gh-pages/index.html
	touch /tmp/gh-pages/.nojekyll
	find /tmp/gh-pages -type d | xargs chmod -f 0755
	find /tmp/gh-pages -type f | xargs chmod -f 0644
	cd /tmp/gh-pages/ && git add . && git commit -a -m "Automated commit on $$(date)" && git push origin gh-pages

.PHONY: push-travis
push-travis:
	git clone https://github.com/simplepie/simplepie-ng.git --branch gh-pages --single-branch /tmp/gh-pages
	rm -Rf /tmp/gh-pages/*
	cp -Rf ./docs/_build/* /tmp/gh-pages/
	cp -Rf ./docs/redirect.tmpl /tmp/gh-pages/index.html
	touch /tmp/gh-pages/.nojekyll
	find /tmp/gh-pages -type d | xargs chmod -f 0755
	find /tmp/gh-pages -type f | xargs chmod -f 0644
	cd /tmp/gh-pages/ && \
		git add . && \
		git remote add upstream "https://$$GH_TOKEN@github.com/simplepie/simplepie-ng.git" && \
		git commit -a -m "Automated commit on $$(date)" && git push upstream gh-pages

#-------------------------------------------------------------------------------
# Linting and Static Analysis

.PHONY: lint
lint:
	@ mkdir -p reports

	@ echo " "
	@ echo "=====> Running PHP CS Fixer..."
	- bin/php-cs-fixer fix -vvv

	@ echo " "
	@ echo "=====> Running PHP Code Sniffer..."
	- bin/phpcs --report-xml=reports/phpcs-src.xml -p --colors --encoding=utf-8 $$(find src/ -type f -name "*.php" | sort | uniq)
	- bin/phpcs --report-xml=reports/phpcs-tests.xml -p --colors --encoding=utf-8 $$(find tests/ -type f -name "*.php" | sort | uniq)
	- bin/phpcbf --encoding=utf-8 --tab-width=4 src/ 1>/dev/null
	- bin/phpcbf --encoding=utf-8 --tab-width=4 tests/ 1>/dev/null
	@ echo " "
	@ echo "---------------------------------------------------------------------------------------"
	@ echo " "
	@ php tools/reporter.php

.PHONY: analyze
analyze: lint test
	@ echo " "
	@ echo "=====> Running PHP Copy-Paste Detector..."
	- bin/phpcpd --names=*.php --log-pmd=$$(pwd)/reports/copy-paste.xml --fuzzy src/

	@ echo " "
	@ echo "=====> Running PHP Lines-of-Code..."
	- bin/phploc --progress --names=*.php --log-xml=$$(pwd)/reports/phploc-src.xml src/ > $$(pwd)/reports/phploc-src.txt
	- bin/phploc --progress --names=*.php --log-xml=$$(pwd)/reports/phploc-tests.xml tests/ > $$(pwd)/reports/phploc-tests.txt

	@ echo " "
	@ echo "=====> Running PHP Code Analyzer..."
	- php bin/phpca src/ --no-progress | tee reports/phpca-src.txt
	- php bin/phpca tests/ --no-progress | tee reports/phpca-tests.txt

	@ echo " "
	@ echo "=====> Running PHP Metrics Generator..."
	@ # phpmetrics/phpmetrics
	- bin/phpmetrics --config $$(pwd)/phpmetrics.yml --template-title="SimplePie NG" --level=10 src/

	@ echo " "
	@ echo "=====> Running Open-Source License Check..."
	- composer licenses -d www | grep -v BSD-.-Clause | grep -v MIT | grep -v Apache-2.0 | tee reports/licenses.txt

	@ echo " "
	@ echo "=====> Comparing Composer dependencies against the PHP Security Advisories Database..."
	- curl -sSL -H "Accept: text/plain" https://security.sensiolabs.org/check_lock -F lock=@composer.lock | tee reports/sensiolabs.txt

#-------------------------------------------------------------------------------
# Git Tasks

.PHONY: tag
tag:
	@ if [ $$(git status -s -uall | wc -l) != 0 ]; then echo 'ERROR: Git workspace must be clean.'; exit 1; fi;

	@echo "This release will be tagged as: $$(cat ./VERSION)"
	@echo "This version should match your release. If it doesn't, re-run 'make version'."
	@echo "---------------------------------------------------------------------"
	@read -p "Press any key to continue, or press Control+C to cancel. " x;

	@echo " "
	@chag update $$(cat ./VERSION)
	@echo " "

	@echo "These are the contents of the CHANGELOG for this release. Are these correct?"
	@echo "---------------------------------------------------------------------"
	@chag contents
	@echo "---------------------------------------------------------------------"
	@echo "Are these release notes correct? If not, cancel and update CHANGELOG.md."
	@read -p "Press any key to continue, or press Control+C to cancel. " x;

	@echo " "

	git add .
	git commit -a -m "Preparing the $$(cat ./VERSION) release."
	chag tag --sign

.PHONY: version
version:
	@echo "Current version: $$(cat ./VERSION)"
	@read -p "Enter new version number: " nv; \
	printf "$$nv" > ./VERSION
