<pre>
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('db.inc');
require('split_number.php');
require('img_url.php');

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

$imgdata = fetch_img($fields['url']);

/**
 * Write data to a database for debugging purposes
 */

//ip,prefix,number,url_id,url_string,file
$sth = $dbh->prepare('INSERT request (ip,prefix,number,url_id,url_string) '.
		     'values (:ip, :prefix, :number, :url_id, :url_string)');

$sth->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
$sth->bindParam(':prefix', $number_r['PREFIX'], PDO::PARAM_STR);
$sth->bindParam(':number', $number_r['NUMBER'], PDO::PARAM_STR);
$sth->bindParam(':url_id', $fields['id'], PDO::PARAM_STR);
$sth->bindParam(':url_string', $fields['string'], PDO::PARAM_STR);
//$sth->bindParam(':file', , PDO::PARAM_STR);

$sth->execute();

/**
 * Entertain the user
 */

print($fields['url']."\n");

?>
</pre>