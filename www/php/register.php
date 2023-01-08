<?php
require "connection_with_db.php";

$post_username = htmlspecialchars($_POST['username']); // εισαγωγήδ δεδομένων απο το browser
$post_password = htmlspecialchars($_POST['password']); // εισαγωγήδ δεδομένων απο το browser

$register_query = "INSERT INTO players (username, password) VALUES ('$post_username','$post_password')";
$statement = $dbcon->query($register_query);  

if (session_status() !== PHP_SESSION_ACTIVE) {  // αν η συνεδρια υπάρχει και είναι ενεργή, τότε:
		session_start();
}
if ($statement !== false ) {  // αν ο χρήστης έδωσε στοιχεία, τότε:
	$_SESSION['registerMessage'] = 'Επιτυχής εγγραφή!';
	$_SESSION['status'] = 1;   //none --> δεν υπάρχει
	
}
else {
	
	$_SESSION['status'] = 2; // είναι ενεργη
	$_SESSION['registerMessage'] = 'Πρόβλημα σύνδεσης';
	                                    
	
	session_write_close();
		header('Location:: ../register_user.html');     
	exit;
}
?>