<?php // -*- coding: utf-8 -*-

/**
 * Calculate "tail hash from the image.
 * That is taking the rightmost part of the image and taking hash from it.
 */
function tailhash($local_img) {
	// some constants
	$box_w = 30;
	$box_h = 18;
	
	// do some tricks (=cropping) to the image
	$img_size = getimagesize($local_img);
	$cut_x = $img_size[0]-$box_w;
	$img = imagecreatefromgif($local_img);
	$img_box = imagecreatetruecolor($box_w, $box_h);
	imagecopy($img_box, $img, 0, 0, $cut_x, 0, $box_w, $box_h);
	
	// output to a PNG and hash it
	$tmpfile = tempnam('numpac_data', 'hash_');  
	chmod($tmpfile, 0644);
	$is_ok = imagepng($img_box, $tmpfile);
	if ($is_ok = FALSE) {
		throw new Exception("Tiivisteen muodostus ei onnistu.");
	}

	$hash = sha1_file($tmpfile);
	unlink($tmpfile);

	return $hash;
}