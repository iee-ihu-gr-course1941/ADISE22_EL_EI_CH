<?php 

session_start();

include("connection_with_db.php");
$Error = "";


if($_SERVER['REQUEST_METHOD'] == "POST")
{
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	if(!empty($username) && !empty($password) && !is_numeric($username))
	{

					header("Location: https://users.it.teithe.gr/~it185421/adise22/ADISE22_EL_EI_CH/www/html/api.html");
					exit();
		
	}else
	{
		$Error = "username or password cannot be empty!";
	}
}
		
?>