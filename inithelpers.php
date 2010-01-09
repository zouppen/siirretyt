<?php // -*- coding: utf-8 -*-

/**
 * Init XSLT stylesheet for outputting pages
 */

function init_xslt() {

	$xslt = new XSLTProcessor();
	$xsl = new DOMDocument();
	$xsl->load( 'xsl/outpage.xsl', LIBXML_NOCDATA | LIBXML_NONET);
	$xslt->importStylesheet( $xsl );

	$GLOBALS['xslt_out'] = $xslt;
}

?>