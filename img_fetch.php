<?php // -*- coding: utf-8 -*-

/**
 * Get an image from given URL.
 */

function img_fetch($url) {

	// Creating a temp file

	$tmpfname = tempnam("numpac_data", "img_");
	chmod($tmpfname , 0644);
	$tmpfile_h = fopen($tmpfname, "w");
	
	// Getting the image

	$curl_h = curl_init($url);

	curl_setopt($curl_h, CURLOPT_HEADER, 0); // With no header
	curl_setopt($curl_h, CURLOPT_FAILONERROR, 1);
	curl_setopt($curl_h, CURLOPT_FILE, $tmpfile_h);

	$is_ok = curl_exec($curl_h);
	fclose($tmpfile_h);

	if (!$is_ok) throw new Exception("Datan noutaminen epäonnistui. Numpac on ehkä muuttanut sivujaan.");
	
	return $tmpfname;
}