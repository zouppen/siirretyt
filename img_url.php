<?php // -*- coding: utf-8 -*-

/**
 * Preparing the fields
 */

function img_url($number_r) {

	$number_r['Submit'] = 'Hae';
	$number_r['LANGUAGE'] = 'Finnish';

	$number_query = http_build_query($number_r);
	
	// Getting the WWW page

	//$curl_h = curl_init("http://www.siirretytnumerot.fi/QueryServlet");
	$curl_h = curl_init("http://users.jyu.fi/~jopesale/web/siirretyt/vakio.html");

	curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, 1); // As string
	curl_setopt($curl_h, CURLOPT_HEADER, 0); // With no header
	curl_setopt($curl_h, CURLOPT_POST, 1);
	curl_setopt($curl_h, CURLOPT_FAILONERROR, 1);
	curl_setopt($curl_h, CURLOPT_POSTFIELDS, $number_query);

	$orig_xml = curl_exec($curl_h);
	curl_close($curl_h);
	
	if ($orig_xml == FALSE) {
		throw new Exception("Tiedon hakeminen Numpacin sivuilta ei ".
				    "onnistunut.");
	}

	$orig_doc = new DOMDocument();
	$orig_doc->loadHTML($orig_xml);
	
	// Fetch the URL from the document with XSLT

	$xslt = new XSLTProcessor();
	$xsl = new DOMDocument();
	$xsl->load( 'xsl/get_number_url.xsl', LIBXML_NOCDATA || LIBXML_NONET );
	$xslt->importStylesheet( $xsl );

	$raw_url = $xslt->transformToXML( $orig_doc );

	return $raw_url;
}

/**
 * Parse given url (split id and string)
 */
function img_url_parse($raw_url) {

	// Split the fields of URL

	$pattern = '/^QueryServlet\?ID=(.*)&STRING=(.*)$/';
	$is_ok = preg_match($pattern, $raw_url, $matches);
	
	// Check if it matches
	if (!$is_ok) {
		throw new Exception('Numpacin antaman osoitteen lukeminen '.
				    'ei onnistunut.');
	}

	return array(
		'id' => urldecode($matches[1]),
		'string' => urldecode($matches[2]));
}
?>