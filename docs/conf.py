# -*- coding: utf-8 -*-

import sys, os

extensions = []
templates_path = ['_templates']
source_suffix = '.rst'
master_doc = 'index'

project = u'Leach'
copyright = u'2012, Pierre Minnieur'
version = '2.1'
release = '2.1.0-dev'

exclude_patterns = ['_build']

#html_logo = 'static/logo.png'
#html_favicon = 'favicon.ico'
html_static_path = ['_static']
htmlhelp_basename = 'Leachdoc'

latex_documents = [
  ('index', 'Leach.tex', u'Leach Documentation',
   u'Pierre Minnieur', 'manual'),
]

man_pages = [
    ('index', 'leach', u'Leach Documentation',
     [u'Pierre Minnieur'], 1)
]

texinfo_documents = [
  ('index', 'Leach', u'Leach Documentation',
   u'Pierre Minnieur', 'Leach', 'One line description of project.',
   'Miscellaneous'),
]
