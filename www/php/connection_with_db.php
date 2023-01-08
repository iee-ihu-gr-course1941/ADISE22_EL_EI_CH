<?php

$host='localhost';
$db = 'domino';


$user='it185421';
$pass='';


if(gethostname()=='users.iee.ihu.gr') {
	$mysqli_connect = new mysqli($host, $user, $pass, $db,null,'/home/student/it/2018/it185421/mysql/run/mysql.sock');
} else {
    $mysqli_connect = new mysqli($host, $user, $pass, $db);
}

if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }
?>