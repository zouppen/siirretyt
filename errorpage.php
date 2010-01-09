<?php // -*- coding: utf-8 -*-
/**
 * Preparing the fields
 */

function okpage($doc) {
	// Beautiful output with XSLT

	global $xslt_out;

	$xslt_out->transformToURI( $doc, 'php://output' );
}

function errorpage($msg) {
	$error_doc = new DOMDocument();
	$root = $error_doc->createElementNS('http://iki.fi/zouppen/2010/php',
					    'error',$msg);
	$error_doc->appendChild($root);

	// Beautiful output with XSLT

	global $xslt_out;
	global $basic_info;

	$xslt_out->transformToURI( $error_doc, 'php://output' );

	// Put error message to the database, too
	global $dbh;
	
	$sth = $dbh->prepare('INSERT error (ip,raw_number,raw_url) '.
		     'values (:ip, :raw_number, :raw_url)');

	$sth->bindParam(':ip', $basic_info['ip'], PDO::PARAM_STR);
	$sth->bindParam(':raw_number', $basic_info['number'], PDO::PARAM_STR);
	$sth->bindParam(':raw_url', $basic_info['img_url'], PDO::PARAM_STR);
	
	$sth->execute();
}