<pre>
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require("tmp_prefixes.php");
print("jep".$max_length);

//print_r($GLOBALS);

if (empty($_GET['n'])) {
  print("numero puuttuu. TODO parempi sivu.");
  exit(0);
}

$number = $_GET['n'];

print("numero annettu\n");

$match = false;

foreach($prefixes as $prefix) {
  if (!strncmp($number, $prefix, strlen($prefix))) {
    // Is matching this prefix
    $match=true;
    break;
  }
}

if ($match==false) {
  print("ei osunut numero ".$number."\n");
  exit(0);
 }

$postfix=substr($number,strlen($prefix));

if (strlen($postfix) > $max_length) {
  // siirretytnumerot.fi's maximum length reached
  print("liian pitkä numero\n");
  exit(0);
 }

print("Osui ja upposi: operaattori on ".$prefix.", numero on ".$postfix."\n");

?>
</pre>