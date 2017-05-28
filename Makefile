all:
	@cat Makefile | grep : | grep -v PHONY | grep -v @ | sed 's/:/ /' | awk '{print $$1}' | sort

#-------------------------------------------------------------------------------

.PHONY: install
install:
	composer self-update
	composer install -o

.PHONY: test
test:
	php bin/phpunit

#-------------------------------------------------------------------------------

.PHONY: lint
lint:
	@ echo " "
	@ echo "=====> Running PHP CS Fixer..."
	- bin/php-cs-fixer fix -vvv

	@ echo " "
	@ echo "=====> Running PHP Code Sniffer..."
	- mkdir -p reports/
	- bin/phpcs -p --encoding=utf-8 --tab-width=4 --report=checkstyle --report-file=reports/phpcs.xml src/
	- bin/phpcbf -p --encoding=utf-8 --tab-width=4 src/

.PHONY: analyze
analyze: #lint test
	@ echo " "
	@ echo "=====> Running PHP Copy-Paste Detector..."
	- bin/phpcpd --names=*.php --log-pmd=$$(pwd)/reports/copy-paste.xml --fuzzy src/

	@ echo " "
	@ echo "=====> Running PHP Lines-of-Code..."
	- bin/phploc --progress --names=*.php --log-xml=$$(pwd)/reports/phploc-src.xml src/ > $$(pwd)/reports/phploc-src.txt
	- bin/phploc --progress --names=*.php --log-xml=$$(pwd)/reports/phploc-tests.xml tests/ > $$(pwd)/reports/phploc-tests.txt

	@ echo " "
	@ echo "=====> Running PHP Code Analyzer..."
	- php bin/phpca src/ --no-progress
	- php bin/phpca tests/ --no-progress

	@ echo " "
	@ echo "=====> Running PHP Metrics Generator..."
	- bin/phpmetrics --config ./phpmetrics.yml --template-title="Metrics" --level=10 src/

	@ echo " "
	@ echo "=====> Running PDepend..."
	- bin/pdepend \
	    --configuration=$$(pwd)/pdepend.xml.dist \
	    --dependency-xml=$$(pwd)/reports/pdepend.full.xml \
	    --summary-xml=$$(pwd)/reports/pdepend.summary.xml \
	    --jdepend-chart=$$(pwd)/reports/jdepend.chart \
	    --jdepend-xml=$$(pwd)/reports/jdepend.xml \
	    --overview-pyramid=$$(pwd)/reports/overview \
	    src/ \
	;

	@ echo " "
	@ echo "=====> Running Open-Source License Check..."
	- composer licenses | grep -v BSD-.-Clause | grep -v MIT | grep -v Apache-2.0

	@ echo " "
	@ echo "=====> Comparing Composer dependencies against the PHP Security Advisories Database..."
	- curl -H "Accept: text/plain" https://security.sensiolabs.org/check_lock -F lock=@composer.lock

	@ echo " "
	@ echo "=====> Running Quality Analyzer..."
	- bin/analyze --coverage=tests/report/clover.xml --checkstyle=reports/phpcs.xml --tests=tests/report/logfile.xml --cpd=reports/copy-paste.xml --phploc=reports/phploc-src.xml --exclude_analyzers=pdepend,phpmd,dependencies analyze src/
	- bin/analyze bundle reports/analyze/
	@ echo "*******************************************************************"
	@ echo "Start a local web server to view the results"
	@ echo "php -S 4000 -t $$(pwd)/reports/analyze/"
	@ echo "*******************************************************************"

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
