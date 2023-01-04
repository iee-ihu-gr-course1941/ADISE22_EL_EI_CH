<?php
	
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
	if (isset($_SESSION['status'])) {
		
		include "active.php"; 
		
		if ($_SESSION['status'] == 3) {
		echo $_SESSION['EndMessage'];
		
		?>
			<form action="../wait_the_other_player.html" method="POST">					
				<label for="newgame"><button class="button2">Επανέναρξη</button></label><br/>
				<input type="submit" name="newgame" style="display:none">
			</form>
		<?php
		}
		else{ 
			echo "Error.";
		}
		exit;
	}
	else {
		http_response_code(403);
		exit;
	}
?>