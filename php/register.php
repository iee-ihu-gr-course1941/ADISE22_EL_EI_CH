<?php
require "connection_with_db.php";

$post_username = htmlspecialchars($_POST['uname']);
$post_password = htmlspecialchars($_POST['pass']);

$register_query = "INSERT INTO players (username,password) VALUES ('$post_username','$post_password')";
$statement = $dbcon->query($register_query);
//$register_affected_rows = $statement->affected_rows;
if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
}
if ($statement !== false ) {
	$_SESSION['registerMessage'] = 'Επιτυχής εγγραφή!';
	$_SESSION['status'] = 1;
	
	include 'login.php';
}
else {
	//error
	$_SESSION['status'] = 2;
	$_SESSION['registerMessage'] = 'Πρόβλημα σύνδεσης';
	$_SESSION['user'] = -1;
	header('Location:: ../register_user.html');                                         // na to doume to path
	
	session_write_close();
	exit;
}
?>