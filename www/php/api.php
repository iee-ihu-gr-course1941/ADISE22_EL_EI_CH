<?php

require "libraries.php";
require "state_of_db.php";
if (!isset($connected) || $connected == false) {
    require "connection_with_db.php";
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['gameID'])) {
    $player1 = $_SESSION['player1'];
    $player2 = $_SESSION['player2'];
    $query = "SELECT gameID FROM state WHERE player1 = '$player1' AND player2 = '$player2'";
    $game_check = $dbcon->query($query);
    if ($game_check == true) {
        $game_numrows = $game_check->num_rows;
        if ($game_numrows == 1) {
            $game_row = $game_check->fetch_assoc();
            
            if (!empty($game_row)) {
                $_SESSION['gameID'] = $game_row['gameID'];
            }
        }
    } else {
        echo $dbcon->error;
    }
}

$JSONstate = selectState($_SESSION['gameID']);
$state = jsonToState($JSONstate);

$json_output = array();

$json_output['end'] = $state["end"];

if (isset($button)) {
   
    if ($button== "start") {
        $json_output['board'] = getBoardToJSON($state);
       
        $json_output['hand'] = getPlayerHandToJSON($state, $_SESSION['current_P']);
		$json_output['cp'] = $player ;
    } else if ($button == "play") {
        $state = playDomino($state, $_GET['front'], $_GET['back']);
        $json_output['board'] = getBoardToJSON($state);
        $json_output['hand'] = getPlayerHandToJSON($state, $_SESSION['current_P']);
		$json_output['cp'] = $player ;
       
    } else if ($button == "flip") {
        $state = flipDominoInMyHand($state, $_GET['front'], $_GET['back']);
        $json_output['hand'] = getPlayerHandToJSON($state, $_SESSION['current_P']);
		$json_output['cp'] = $player ;
        
    } else if ($button == "draw") {
        $state = takeFromPile($state);
        
        $json_output['hand'] = getPlayerHandToJSON($state, $_SESSION['current_P']);
		$json_output['cp'] = $player ;
    }
	
	echo json_encode($json_output);
}
session_write_close();
exit;
?>