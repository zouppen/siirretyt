<?php // -*- coding: utf-8 -*-
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('db.inc');
require('inithelpers.php');
require('split_number.php');
require('img_url.php');
require('img_fetch.php');
require('errorpage.php');

init_xslt();

// setting some basic info for error handling
$basic_info = array('ip' => $_SERVER['REMOTE_ADDR']);

/**
 * Connect the db.
 */

$dbh = new PDO('mysql:host=zouppen.iki.fi;dbname=siirretyt', 'siirretyt',
	       $mysql_password);

/**
 * Start processing
 */

if (empty($_GET['telephone'])) {
	errorpage("Haettava numero puuttuu.");
}

// Taking out the international prefix, if any
$in_number=$_GET['telephone'];
$basic_info['number'] = preg_replace('/^(\+358|00358)/', '0', $in_number);

try {
	$number_r = split_number($basic_info['number']);
	$basic_info['img_url'] = img_url($number_r);
	$fields = img_url_parse($basic_info['img_url']);
	
	//$img_info = img_fetch('http://siirretytnumerot.fi/'.
	//		      $basic_info['img_url']);
	$local_img = img_fetch('http://users.jyu.fi/~jopesale/web/siirretyt/'.
			       'numpac_data/esim.gif');
} catch (Exception $e) {
	errorpage($e->getMessage());
}

/**
 * Write data to a database for debugging purposes
 */

$sth = $dbh->prepare('INSERT request (ip,prefix,number,url_id,url_string, file) '.
		     'values (:ip, :prefix, :number, :url_id, :url_string, :file)');

$sth->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
$sth->bindParam(':prefix', $number_r['PREFIX'], PDO::PARAM_STR);
$sth->bindParam(':number', $number_r['NUMBER'], PDO::PARAM_STR);
$sth->bindParam(':url_id', $fields['id'], PDO::PARAM_STR);
$sth->bindParam(':url_string', $fields['string'], PDO::PARAM_STR);
$sth->bindParam(':file', $local_img, PDO::PARAM_STR);

$sth->execute();

/**
 * Entertain the user
 */

$my_ns = 'http://iki.fi/zouppen/2010/php';
$res_doc = new DOMDocument();
$root = $res_doc->createElementNS($my_ns,'result');
$res_doc->appendChild($root);

$root->setAttribute('img',$local_img);
$root->setAttribute('prefix',$number_r['PREFIX']);
$root->setAttribute('number',$number_r['NUMBER']);
$root->setAttribute('number_raw',$basic_info['number']);

okpage($res_doc);

?>
