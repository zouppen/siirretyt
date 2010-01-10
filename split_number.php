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
		throw new Exception("Numeron operaattoritunnusta ei tunneta.");
	}
	
	$number_end=substr($number,strlen($prefix));
	
	if (strlen($number_end) > $GLOBALS['max_length']) {
		// siirretytnumerot.fi's maximum length reached
		throw new Exception("Numero on liian pitkä.");
		return $result;
	}

	$result['PREFIX'] = $prefix;
	$result['NUMBER'] = $number_end;
	return $result;
}
