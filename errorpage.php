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
	global $basic_info;
	
	$error_doc = new DOMDocument();
	$root = $error_doc->createElementNS('http://iki.fi/zouppen/2010/php',
					    'error',$msg);
	$error_doc->appendChild($root);
	if (isset($basic_info['number'])) {
		$root->setAttribute('number_raw',$basic_info['number']);
	}

	// Beautiful output with XSLT
	global $xslt_out;

	$xslt_out->transformToURI( $error_doc, 'php://output' );

	// Put error message to the database, too
	global $dbh;
	
	$sth = $dbh->prepare('INSERT error (ip,raw_number,msg,raw_url) '.
		     'values (:ip,:raw_number,:msg,:raw_url)');

	$sth->bindParam(':ip', $basic_info['ip'], PDO::PARAM_STR);
	$sth->bindParam(':raw_number', $basic_info['number'], PDO::PARAM_STR);
	$sth->bindParam(':msg', $msg, PDO::PARAM_STR);
	$sth->bindParam(':raw_url', $basic_info['img_url'], PDO::PARAM_STR);
	
	$sth->execute();

	exit(0); // Stop page.
}