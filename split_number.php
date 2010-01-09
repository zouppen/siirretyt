<?php // -*- coding: utf-8 -*-

require("tmp_prefixes.php");

function split_number($number) {
	$match = false;
	$result = array();

	foreach($GLOBALS['prefixes'] as $prefix) {
		if (!strncmp($number, $prefix, strlen($prefix))) {
			// Is matching this prefix
			$match=true;
			break;
		}
	}
	
	if ($match==false) {
		$result['error'] = "Numeron operaattoritunnusta ei tunneta.";
		return $result;
	}
	
	$number_end=substr($number,strlen($prefix));
	
	if (strlen($number_end) > $GLOBALS['max_length']) {
		// siirretytnumerot.fi's maximum length reached
		$result['error'] = "Numero on liian pitkä.";
		return $result;
	}

	$result['PREFIX'] = $prefix;
	$result['NUMBER'] = $number_end;
	return $result;
}
