<?php // -*- coding: utf-8 -*-
/**
 * Preparing the fields
 */

function errorpage($msg) {
	$error_doc = new DOMDocument();
	$root = $error_doc->createElementNS('http://iki.fi/zouppen/2010/php','error',$msg);
	$error_doc->appendChild($root);

	// Beautiful output with XSLT

	global $xslt_out;
	$raw_url = $xslt_out->transformToURI( $error_doc, 'php://output' );

	// Put error message to the database, too
}