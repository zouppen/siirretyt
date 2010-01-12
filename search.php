<?php // -*- coding: utf-8 -*-
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require('db.inc');
require('inithelpers.php');
require('split_number.php');
require('img_url.php');
require('img_fetch.php');
require('errorpage.php');
require('tailhash.php');
require('operator.php');

// Enable cache to avoid repetitive requests
$expires = 600; // ten minutes
header("Pragma: public");
header("Cache-Control: maxage=".$expires);
header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');

init_xslt();

// setting some basic info for error handling
$basic_info = array('ip' => $_SERVER['REMOTE_ADDR']);

if (isset($_GET['xml'])) $basic_info['xml'] = true;
else $basic_info['xml'] = false;

/**
 * Connect the db.
 */

$dbh = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_dbname,
	       $mysql_user, $mysql_password);

/**
 * Start processing
 */

if (empty($_GET['telephone'])) {
	errorpage("Haettava numero puuttuu.");
}

// Taking out the international prefix, if any
$in_number=$_GET['telephone'];
$in_number_fi = preg_replace('/^(\+358|00358)/', '0', $in_number);

// Take dashes and spaces out
$basic_info['number'] = preg_replace('/[- ]/', '', $in_number_fi);

// Check if number is somewhat senseful
if(!preg_match('/^[0-9]+$/', $basic_info['number'])) {
	if (isset($_GET['quick'])) {
		errorpage('Et tainnut hakea numeroa. Valitsitko väärän '.
			  'hakukoneen? Koeta hakea vaikka Googlella.');
	} else {
		errorpage('Et tainnut hakea numeroa.');
	}
}

// Find out the operator prefix
try {
	$number_r = split_number($basic_info['number']);
} catch (Exception $e) {
	errorpage($e->getMessage());
}

// Fetch data from Numpac
try {
	$basic_info['img_url'] = img_url($number_r);
	$fields = img_url_parse($basic_info['img_url']);
	
	$local_img = img_fetch('http://www.siirretytnumerot.fi/'.
			       $basic_info['img_url']);
} catch (Exception $e) {
	errorpage($e->getMessage(),true);
}

// Do the magic to find out the operator
try {
	$hash = tailhash($local_img);
	$operator_r = operator($hash);
} catch (Exception $e) {
	errorpage($e->getMessage());
}

/**
 * Write data to a database for debugging purposes
 */

$sth = $dbh->prepare('INSERT request (ip,prefix,number,url_id,url_string,'.
		     'file,hash) values'.
		     '(:ip,:prefix,:number,:url_id,:url_string,:file,:hash)');

$sth->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
$sth->bindParam(':prefix', $number_r['PREFIX'], PDO::PARAM_STR);
$sth->bindParam(':number', $number_r['NUMBER'], PDO::PARAM_STR);
$sth->bindParam(':url_id', $fields['id'], PDO::PARAM_STR);
$sth->bindParam(':url_string', $fields['string'], PDO::PARAM_STR);
$sth->bindParam(':file', $local_img, PDO::PARAM_STR);
$sth->bindParam(':hash', $hash, PDO::PARAM_STR);

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
$root->setAttribute('op_name',$operator_r['name']);
$root->setAttribute('op_id',$operator_r['op_id']);
$root->setAttribute('op_homepage',$operator_r['homepage']);

if (isset($_GET['quick'])) {
	$root->setAttribute('quick',$_GET['quick']);
}

outpage($res_doc);

?>
