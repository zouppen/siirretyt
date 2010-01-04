#!/bin/bash
IFS=$'\n'
xsltproc --html generate_prefixes.xsl 'http://www.siirretytnumerot.fi/' >tmp_prefixes.php
[[ $? -eq 0 ]] || exit 1
echo Onnistui.