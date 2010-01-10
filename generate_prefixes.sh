#!/bin/bash
IFS=$'\n'
xsltproc --html xsl/generate_prefixes.xsl 'http://www.siirretytnumerot.fi/' >tmp_prefixes.php
[[ $? -eq 0 ]] || exit 1
echo Success, if there were only warnings.
