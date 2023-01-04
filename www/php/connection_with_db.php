<?php
	$host = 'localhost';
	$user = 'it185421@users.it.teithe.gr';
        //$user = 'user';
	$pass = '';
	$database = 'domino';
	$port = '22';                                                                         //3306
	
	$dbcon = new mysqli($host,$user,$pass,$database,$port);
	$connected = false;
	if ($dbcon !== false) {
		//echo "DB connected.";
		$connected = true;
	}
	elseif (!empty($dbcon->error)) {
		echo $dbcon->errno.' '.$dbcon->error;
	}
	else {
		echo "Database is empty.";
		exit;
	}
	?>