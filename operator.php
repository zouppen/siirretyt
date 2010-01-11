<?php // -*- coding: utf-8 -*-

/**
 * Get name of the operator from the database
 */
function operator($hash) {
	
	global $dbh;
	
	$sth = $dbh->prepare('SELECT name,op_id,homepage from operator '.
			     'where hash=:hash');
	
	if($sth == FALSE)
		throw new Exception('Operaattoritietokanta ei toimi.');

	$sth->bindParam(':hash', $hash, PDO::PARAM_STR);
	if($sth->execute() == FALSE) {
		throw new Exception('Operaattorin nimen hakeminen ei '.
				    'onnistunut');
	}

	// Returns false if no operator name is found
	return $sth->fetch(PDO::FETCH_ASSOC);
}
