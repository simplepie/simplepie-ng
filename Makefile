all:
	@cat Makefile | grep : | grep -v PHONY | grep -v @ | sed 's/:/ /' | awk '{print $$1}' | sort

#-------------------------------------------------------------------------------

.PHONY: install-composer
install-composer:
	- SUDO="" && [[ $$UID -ne 0 ]] && SUDO="sudo"; \
	curl -sSL https://raw.githubusercontent.com/composer/getcomposer.org/master/web/installer \
	    | $$SUDO $$(which php) -- --install-dir=/usr/local/bin --filename=composer

.PHONY: install
install:
	composer self-update
	composer install -oa

.PHONY: test
test:
	php bin/phpunit

.PHONY: dump
dump:
	composer dump-autoload -oa

.PHONY: install-hooks
install-hooks:
	printf '#!/usr/bin/env bash\nmake lint\nmake test' > .git/hooks/pre-commit
	chmod +x .git/hooks/pre-commit

.PHONY: entities
entities:
	wget -O resources/entities.json https://www.w3.org/TR/html5/entities.json
	tools/entities.php
	cat resources/entities.dtd | uniq > resources/entities2.dtd
	mv resources/entities2.dtd resources/entities.dtd

#-------------------------------------------------------------------------------

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
	touch /tmp/gh-pages/.nojekyll
	find /tmp/gh-pages -type d | xargs chmod -f 0755
	find /tmp/gh-pages -type f | xargs chmod -f 0644
	cd /tmp/gh-pages/ && git add . && git commit -a -m "Automated commit on $$(date)" && git push origin gh-pages

#-------------------------------------------------------------------------------

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

#-------------------------------------------------------------------------------

.PHONY: version
version:
	@echo "Current version: $$(cat ./VERSION)"
	@read -p "Enter new version number: " nv; \
	printf "$$nv" > ./VERSION
