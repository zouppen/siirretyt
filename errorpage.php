<?php // -*- coding: utf-8 -*-

/**
 * Good and bad messages
 */
function outpage($doc) {
	// Beautiful output with XSLT
	
	global $basic_info;
	
	if ($basic_info['xml']) {
		// Raw XML output
		header('Content-type: application/xml');
		$doc->save('php://output');
	} else {
		// Beautiful XHTML output
		global $xslt_out;
		$xslt_out->transformToURI( $doc, 'php://output' );
	}
}

function errorpage($msg,$numpac_error = false) {
	global $basic_info;
	
	$error_doc = new DOMDocument();
	$root = $error_doc->createElementNS('http://iki.fi/zouppen/2010/php',
					    'error',$msg);
	$error_doc->appendChild($root);
	if (isset($basic_info['number'])) {
		$root->setAttribute('number_raw',$basic_info['number']);
	}

	if (isset($_GET['quick'])) {
		$root->setAttribute('quick',$_GET['quick']);
	}

	if ($numpac_error) {
		$root->setAttribute('numpac_error',1);
	}

	outpage($error_doc);
	  
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