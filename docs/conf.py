# -*- coding: utf-8 -*-

import sys, os, copy
import sphinx_rtd_theme
from collections import OrderedDict
from sphinx.highlighting import lexers
from pygments.lexers.configs import TerraformLexer
from pygments.lexers.javascript import JavascriptLexer
from pygments.lexers.python import PythonLexer
from pygments.lexers.shell import BashLexer, BashSessionLexer
from pygments.lexers.web import PhpLexer

extensions = [
    'sphinx.ext.autosectionlabel',
    'sphinx.ext.githubpages',
    'sphinx_dust',
    'sphinx_tabs.tabs',
    'sphinxcontrib.jinja',
]

def setup(app):
   #app.add_javascript("custom.js")
   app.add_stylesheet("theme_overrides.css")

# sphinx-dust
dust_days_limit = 45
dust_emit_warnings = True
dust_include_output = True
dust_output_format = "Written on {written_on}, proofread on {proofread_on}."
dust_datetime_format = "%Y-%m-%d"
dust_node_classes = ['reviewed']

# number configurations
numfig = True
numfig_format = {
    'figure': '<b>Fig. %s:</b>',
    'code-block': '<b>Example %s:</b>',
    'table': '<b>Table %s:</b>',
    'section': '<b>§%s:</b>',
}

# languages
highlight_language = 'none'
lexers['bash'] = BashLexer()
lexers['console'] = BashLexer()
lexers['hcl'] = TerraformLexer()
lexers['javascript'] = JavascriptLexer()
lexers['json'] = JavascriptLexer()
lexers['php'] = PhpLexer(startinline=True, funcnamehighlighting=True)
lexers['php-annotations'] = PhpLexer(startinline=True, funcnamehighlighting=True)
lexers['python'] = PythonLexer()

#templates_path = ['_templates']
source_suffix = '.rst'
master_doc = 'index'
project = u'SimplePie NG'
copyright = u'2017 Ryan Parman'
version = '2.0'
html_title = 'SimplePie: User and Developer Guide'
html_short_title = 'SimplePie'
html_output_encoding = 'utf-8'
html_search_language = 'en'
language = 'en'

exclude_patterns = ['_build']

on_rtd = os.environ.get('READTHEDOCS', None) == 'True'
if not on_rtd:  # only import and set the theme if we're building docs locally
    import sphinx_rtd_theme
    html_theme = 'sphinx_rtd_theme'
    html_theme_path = [sphinx_rtd_theme.get_html_theme_path()]
    html_theme_options = {
        'collapse_navigation': False,
        'display_version': False,
    }
    html_context = {
        'display_github': False,
        'github_host': 'github.com',
        'github_user': 'skyzyx',
        'github_repo': 'simplepie-ng',
        'github_version': 'master',
        'conf_py_path': '/docs/',
    }
