<?php

require "connection_with_db.php";
require 'libraries.php';
require 'state-of_db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

//require 'active_game.php';

if (check_active_game() == false) {

    $player1 = $_SESSION['player1'];
    //brings the active users from the DB to find a second player
    $query = "SELECT username FROM Active_players WHERE username <> '$player1' ";
    $activeP_check = $dbcon->query($query);

    if ($activeP_check == true) {
        $activeP_numrows = $activeP_check->num_rows;
    } else {
        echo $dbcon->error();
    }
    if ($activeP_numrows > 0) {
        $activeP_row = $activeP_check->fetch_assoc();
        //if not in a loop it returns only the first row.
        if (!empty($activeP_row)) {

            $_SESSION['player2'] = $activeP_row['username'];
            $player1 = $_SESSION['player1'];
            $player2 = $_SESSION['player2'];

            $state = dominoState([$player1, $player2]);
            $JSONstate = stateToJSON($state);
            //$_SESSION['current_P'] = $state['current-player'];
            insertTableFromStateWithoutGameID($JSONstate, $player1, $player2);

            /*$query = "DELETE FROM Active_players WHERE username = '$player1'";
            $dbcon->query($query);
            $query = "DELETE FROM Active_players WHERE username = '$player2'";
            $dbcon->query($query);*/
            
            //takes the gameID from the DB and adds it to session.
            $query = "SELECT gameID FROM state WHERE player1 = '$player1' AND player2 = '$player2'";
            $game_check = $dbcon->query($query);

            if ($game_check != false) {
                if ($game_check->num_rows == 1) {
                    $game_row = $game_check->fetch_assoc();
                    //id should not be empty
                    if (!empty($game_row)) {
                        $_SESSION['gameID'] = $game_row['gameID'];
                    }
                } elseif ($game_check->num_rows == 0) {
                    $_SESSION['loginMessage'] = 'Το παιχνίδι δεν έχει οριστεί.';
                } else {
                    $_SESSION['loginMessage'] = 'Πρόβλημα σύνδεσης.';
                }
            } else {
                echo $dbcon->error;
            }
        }
    } elseif ($activeP_numrows == 0) {
        $_SESSION['loginMessage'] = 'Δεν υπαρχουν αρκετοί παίχτες διαθέσιμοι.';
    } else {
        $_SESSION['loginMessage'] = 'Πρόβλημα σύνδεσης.';
    }
}

session_write_close();

if (empty($_SESSION['gameID'])) {
    //header('Location:start.php');
    header('Location: ../wait_the_other_player.html');
} else {
    header('Location:../api.html');
}
?>