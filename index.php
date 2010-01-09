<?php // -*- coding: utf-8 -*-
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require('db.inc');
require('inithelpers.php');
require('split_number.php');
require('img_url.php');
require('img_fetch.php');
require('errorpage.php');

init_xslt();

/**
 * Connect the db.
 */

$dbh = new PDO('mysql:host=zouppen.iki.fi;dbname=siirretyt', 'siirretyt', $mysql_password);

/**
 * Start processing
 */

if (empty($_GET['n'])) {
  print("numero puuttuu. TODO parempi sivu.");
  exit(0);
}

$number_r = split_number($_GET['n']);

if (isset($number_r['error'])) {
	print('Virhe: '.$number_r['error']."\n");
	exit(0);
}

$fields = img_url($number_r);

if (isset($fields['error'])) {
	print('Virhe: '.$fields['error']."\n");
	exit(0);
}

errorpage("vituiksmän");
exit(0);

try {
	//$img_info = img_fetch($fields['url']);
	$local_img = img_fetch('http://users.jyu.fi/~jopesale/pienijoel.jpeg');
} catch (Exception $e) {
	print('Virhe: '.$e->getMessage()."\n");
	exit(0);
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

print($fields['url']."\n");

?>
