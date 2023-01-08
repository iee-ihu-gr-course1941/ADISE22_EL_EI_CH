<?php

    function insertTableFromState($curstate,$gameID){ 
        if(!isset($connected)||$connected == false){   // αν η συνεδρια είναι ενεργη και υπάρχει, τοτε:
            require "connection_with_db.php"; 
        }
               
        $query = "INSERT INTO state (gameID,curState) VALUES ('$gameID','$curstate')"; //βάζει τις τιμές στην βάση
        mysqli_query ($dbcon, $query);  
    
    }

    function updateTableFromState($curstate,$gameID){  //κάνει update τον πίνακα state με βάση το id του παιχνιδιου
        if(!isset($connected)||$connected == false){
            require "connection_with_db.php";
        }
        $query = "UPDATE state SET curState = '$curstate' WHERE gameID = '$gameID' ";
        mysqli_query ($dbcon, $query);  
    }

    function selectState($gameID){  // βρίσκει και επιστρέφει τα δεδομένα 
        if(!isset($connected)||$connected == false){
            require "connection_with_db.php";
        }
        $query = "SELECT curState FROM state WHERE (gameID = '$gameID') ";
        $result =  mysqli_query ($dbcon, $query);;
        $array = ($result->fetch_assoc()); 
        return $array["curState"];
    }

?>