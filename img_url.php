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
	curl_setopt($curl_h, CURLOPT_POSTFIELDS, $number_query);

	$orig_xml = curl_exec($curl_h);
	curl_close($curl_h);
	
	$orig_doc = new DOMDocument();
	$orig_doc->loadHTML($orig_xml);
	
	// Fetch the URL from the document with XSLT

	$xslt = new XSLTProcessor();
	$xsl = new DOMDocument();
	$xsl->load( 'xsl/get_number_url.xsl', LIBXML_NOCDATA || LIBXML_NONET );
	$xslt->importStylesheet( $xsl );

	$raw_url = $xslt->transformToXML( $orig_doc );

	// Split the fields of URL

	$pattern = '/^QueryServlet\?ID=(.*)&STRING=(.*)$/';
	$is_ok = preg_match($pattern, $raw_url, $matches);
	
	// Check if it matches
	if (!$is_ok) {
		return array('error' => 'Numpac on muuttanut sivujaan ja tämä ei toimi ainakaan toistaiseksi.');
	}

	return array(
		'url' => $raw_url,
		'id' => $matches[1],
		'string' => $matches[2]);	
}
?>