all:
	@cat Makefile | grep : | grep -v PHONY | grep -v @ | sed 's/:/ /' | awk '{print $$1}' | sort

#-------------------------------------------------------------------------------

.PHONY: docs
docs: clean umldocs rstdocs sphinxdocs

.PHONY: clean
clean:
	touch touch.rst && \
	find $$(pwd) -type f -name '*.rst' | xargs rm -f

.PHONY: umldocs
umldocs:
	for uml in $$(find src/uml/ -type f -name *.puml); do \
		java -jar /usr/local/plantuml.jar -tsvg -charset UTF-8 -nbthread auto $$uml; \
	done;
	for dot in $$(find src/uml/ -type f -name *.dot); do \
		dot -Tsvg -O -x $$dot; \
	done;
	cd src/uml/ && \
		for file in $$(find $$(pwd) -type f -name '*.dot.svg' | sed -e "s/\.dot\.svg//"); do \
			mv $$file.dot.svg $$file.svg; \
		done;

.PHONY: rstdocs
rstdocs: clean
	cd src/ && \
		cp index.tmpl index.rst && \
		find $$(pwd) -type f -name '*.md' -exec \
			pandoc --from markdown_github --to rst --smart --columns 120 --output '{}'.rst '{}' \;
	cd src/ && \
		find $$(pwd) -type f -name '*.rstx' -exec cp '{}' '{}'.rst \;
		for file in $$(find $$(pwd) -type f -name '*.rstx.rst' | sed -e "s/\.rstx\.rst//"); do \
			mv $$file.rstx.rst $$file.rst; \
		done;
	cd src/ && \
		for file in $$(find $$(pwd) -type f -name '*.md.rst' | sed -e "s/\.md\.rst//"); do \
			mv $$file.md.rst $$file.rst; \
			sed -i "s/.. code::/.. code-block::/" $$file.rst; \
			sed -i "s/| .. reviewer-meta::/.. reviewer-meta::/" $$file.rst; \
			sed -i "s/| .. figure::/.. figure::/" $$file.rst; \
			sed -i "s/| :written-on:/   :written-on:/" $$file.rst; \
			sed -i "s/| :proofread-on/   :proofread-on/" $$file.rst; \
			sed -i "s/| :scale/   :scale/" $$file.rst; \
			sed -i "s/| :alt/   :alt/" $$file.rst; \
			sed -i "s/| :caption: /\n   /" $$file.rst; \
			sed -i "s/    \*\*NOTE:\*\* /.. note::\n    /" $$file.rst; \
			sed -i "s/    \*\*IMPORTANT:\*\* /.. warning::\n    /" $$file.rst; \
			sed -i "s/    \*\*WARNING:\*\* /.. warning::\n    /" $$file.rst; \
			sed -i -r "s/:ref:\`\`([^\`]*)\`\`/:ref:\`\1\`/" $$file.rst; \
		done;

.PHONY: sphinxdocs
sphinxdocs:
	rm -Rf /tmp/src /var/docs/src/_build
	- cp -Rf /var/docs/src /tmp/
	cd /tmp/src/ && make html
	- cp -Rf /tmp/src/_build /var/docs/src/
	cp src/theme_overrides.css src/_build/html/_static/theme_overrides.css

.PHONY: push
push:
	rm -Rf /tmp/gh-pages
	git clone git@github.com:simplepie/simplepie-ng.git --branch gh-pages --single-branch /tmp/gh-pages
	rm -Rf /tmp/gh-pages/*
	cp -Rf ./src/_build/html/* /tmp/gh-pages/
	mkdir -p /tmp/gh-pages/api
	cp -Rf ./src/_build/api/* /tmp/gh-pages/api/
	cp ./src/redirect.tmpl /tmp/gh-pages/api/index.html
	touch /tmp/gh-pages/.nojekyll
	find /tmp/gh-pages -type d | xargs chmod -f 0755
	find /tmp/gh-pages -type f | xargs chmod -f 0644
	cd /tmp/gh-pages/ && git add . && git commit -a -m "Automated commit on $$(date)" && git push origin gh-pages
