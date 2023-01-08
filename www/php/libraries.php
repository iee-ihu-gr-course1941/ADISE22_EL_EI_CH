<?php 

   
    function deck(){    // επιλέγει τα tiles απο την βάση
        if(!isset($connected)||$connected == false){
            require "connection_with_db.php";
        }
        
        
        $query = "SELECT numTiles FROM tiles";
        $result = $dbcon->query($query);
        
        $temp = array();
        
        if ($result->num_rows > 0) 
        {	
            
            while($row = $result->fetch_assoc()) 
            {
                $temp[] = $row["numTiles"];
            }
            $possible_tiles = $temp;
        }
        else
        {
            
        }
        $tilesRange = count($possible_tiles);
        for ($i = 0; $i < $tilesRange; $i++)
        {
            for ($j = $i;$j < $tilesRange; $j++)
            {
                $cards[] = ["front"=>($possible_tiles[$i]),"back"=>($possible_tiles[$j])];
            }
        }
    
        return $cards; // επιστρεφει τα tiles
    }

    function shuffleDeck($deck){  // ανακατέβει τα tiles
        $keys = array_keys($deck);
        shuffle($keys);
        foreach($keys as $key) {
            $new[$key] = $deck[$key];
        }
        $deck = $new;
        return $deck;
    }

    
    function dominoState($playerIds) {     // δείχνει την κατάσταση του απιχνιδιού
        $deck = deck();
        $startingDeck = shuffleDeck($deck);
        $state = ["deck" => $startingDeck,
            "players" => 
            [   0 => [ "id" => $playerIds[0],     // δηλώνει το id του παίκτη και ποσα tiles εχει στα χερια του 
                        "hand" => take(7, $startingDeck)],
                1 => [ "id" => $playerIds[1],
                        "hand" => take(7, drop(7, $startingDeck))]
            ],
            "board" => array(),
            "pile" => drop(14, $startingDeck),  // ποσα tiles εμειναν στην στοίβα
            "current-player" => 0, 
            "end" => FALSE
        ];
        $state = chooseWhoPlaysFirst($state);
        return $state;
    }

    
    function redraw($state){
        $deck = deck();
        $startingDeck = shuffleDeck($deck);
        $state["players"][0]["hand"] = take(7, $startingDeck);
        $state["players"][1]["hand"] = take(7, drop(7, $startingDeck));
        $state["pile"] = drop(12, $startingDeck);
        $state = chooseWhoPlaysFirst($state);
        return $state;
    }

    
    function chooseWhoPlaysFirst($state){
       
        /*$randIndex = rand(0,1);  // επιλέγει ποιος παιχτης θα παίξει πρώτος
        $state["current-player"] = $randIndex;*/
        

        $player2Value = -1;
        $player1Value = -1;

        $player1Hand =  $state["players"][0]["hand"];
        $player2Hand =  $state["players"][1]["hand"];


        for($i=0; $i < 7 ; $i++){ 
            foreach($player1Hand as $num1 => $value1){
                if ($value1["front"] == $i && $value1["back"] == $i){
                    $player1Value = $i;
                }
            }
            foreach($player2Hand as $num2 => $value2){
                if ($value2["front"] == $i && $value2["back"] == $i){
                    $player2Value = $i;
                }
            }
        }
        if (($player2Value == $player1Value)){  
            $state["current-player"] = -1;    // δεν έχει οριστει παιχτης για να ξεκινήσει
        }elseif($player1Value > $player2Value){
            $state["current-player"] = 0;      // παίζει ο παιχτης 0 πρωτος
        }elseif($player2Value > $player1Value){
            $state["current-player"] = 1;      // παίζει ο παιχτης 1 πρωτος
        }

        return $state;
    }
  
    function getCurrentPlayerHand($state) {   // επιστρεφει το χέρι του παίχτη για να το api.php
        $playerIndex = $state["current-player"];
        return $state["players"][$playerIndex]["hand"];
    }


    function getCurrentPlayerId($state) {
        $playerIndex = $state["current-player"];
        return $state["players"][$playerIndex]["id"];
    }

    function setCurrentPlayerHand($state, $newHand) {
        $playerIndex = $state["current-player"];
        $state["players"][$playerIndex]["hand"] = $newHand;
        return $state;
    }

    function addDominoToPlayer($state, $domino) {  // δίνει στο χερι του παίχτη tiles απο την στοίβα
        $playerIndex = $state["current-player"];
        array_push($state["players"][$playerIndex]["hand"] , ...$domino);
        return $state;
    }

	
	function unsetDominoFromHand($state,$domino){  // χρησιμοποιείται στην function adddominotoboard
        $oldHand = getCurrentPlayerHand($state);
        $index = findDominoInHand($state,$domino);
		$oldHand[$index] = ["front" => " ", "back" => " "];
        return setCurrentPlayerHand($state,$oldHand);
    }

    function findDominoInHand($state, $domino) {
        $hand = getCurrentPlayerHand($state);
        foreach($hand as $num1 => $value1){
            if($value1 == $domino){
                return $num1;
            }
        }
       
        return -1;
    }
        
    function takeFromPile($state) {  // διαγραφει το πρωτο tiles που τραβηξε ο παιχτης και κάνει update τα επομενα
        $oldPile = $state["pile"];
        $state["pile"] = drop(1, $oldPile);
        $state = addDominoToPlayer($state, take(1, $oldPile));
        isItOver($state);
        return nextTurn($state);
    }   
  
    function nextTurn($state) {
        $state["current-player"] ^= 1; 
        return $state;
    }
    
    function addDominoToBoard($state, $domino) {  //θελαμε να προσθέσουμε τα tiles στο board
        $board = $state["board"];
        $lenght = count($board);
       
    }

    function flipDomino($domino){  // περιστρεφει το tile 
        $temp = $domino["front"];
        $domino["front"] = $domino["back"];
        $domino["back"] = $temp;
        return $domino;
    }

    
    function isItOver($state){  // ελέγχει αν το παιχίδι τελείωσε
        
		$player1Hand =  $state["players"][0]["hand"];
        $player2Hand =  $state["players"][1]["hand"];
        $pile = $state["pile"];
		if (session_status() !== PHP_SESSION_ACTIVE) {
				session_start();
		}
		
        if(isHandEmpty($player1Hand)){  // ελεγχει αν τελειωσαν τα tile του παιχτη για να βγει νικητης
			$_SESSION['status'] = 3; // το 3 θα πει οτι τελειωσε το παιχνιδι
			$_SESSION['win'] = $state["players"][0]["id"];
			$_SESSION['EndMessage'] = "Τέλος παιχνιδιού! ". $state["players"][0]["id"] . " Συγχαρητήρια! Είσαι ο νικητής! ";
            $state["end"] = TRUE;
			return true;
        }
		elseif(isHandEmpty($player2Hand)){   // ελεγχει αν τελειωσαν τα tile του παιχτη για να βγει νικητης
			$_SESSION['status'] = 3;         // το 3 θα πει οτι τελειωσε το παιχνιδι
			$_SESSION['win'] = $state["players"][1]["id"];
			$_SESSION['EndMessage'] = "Τέλος παιχνιδιού! ". $state["players"][1]["id"] . " Συγχαρητήρια! Είσαι ο νικητής! ";           
            $state["end"] = TRUE;
			return true;
        }
		elseif(empty($pile)){  // αλλιως ελεγχει τον νικητη συμφωνω με τους ποντους του καθε παιχτη
            $winPlayerIndex = countPoints($state);
            if($winPlayerIndex != -1){
               $state["end"] = TRUE;
			   $_SESSION['status'] = 3;
			   $_SESSION['win'] = $state["players"][$winPlayerIndex]["id"];
			   $_SESSION['EndMessage'] = "Τέλος παιχνιδιού! ". $state["players"][$winPlayerIndex]["id"] . " Συγχαρητήρια! Είσαι ο νικητής! ";
			   return true;
            }else{  // αν οι ποντοι ειναι ισοι τοτε εχουμε ισοπαλια 
               $_SESSION['EndMessage'] = "Ισοπαλία";
            }
        }
		return false;
    }

	 function isHandEmpty($hand){
        foreach($hand as $num1 => $value1){
            if(($value1["front"] != " ") || ($value1["back"] != " ")){
                return FALSE;
            }
        }
        return TRUE;
    }
    
    function countPoints($state){  // μετραει τους πόντους του καθε παίχτη
        $player1Hand =  $state["players"][0]["hand"];
        $player2Hand =  $state["players"][1]["hand"];

        $adderPlayer1 = 0;
        $adderPlayer2 = 0;

        $numMax = [0,0];
        foreach($player1Hand as $num1 => $value1){
            if(($value1["front"] != " ") || ($value1["back"] != " ")){
                $adderPlayer1 = $adderPlayer1 + $value1["front"] + $value1["back"]; //προσθετει τους πόντους
            }
        }
        foreach($player2Hand as $num2 => $value2){
            if(($value2["front"] != " " || $value2["back"] != " ")){
                $adderPlayer2 = $adderPlayer2 + $value2["front"] + $value2["back"];  //προσθετει τους πόντους
            }
        }

        if($adderPlayer2 == $adderPlayer1){
            return -1;  //ισοπαλια
        }elseif($adderPlayer2 < $adderPlayer1){
            return 0;
        }elseif($adderPlayer2 > $adderPlayer1){
            return 1;
        }else{
        }

    }
    
    function getPlayerHandToJSON($state,$name) {
        if($state["players"][0]["id"]==$name){
            $index=0;
        }else{
            $index=1;
        }
        $hand = $state["players"][$index]["hand"];
        //$jsonHand = json_encode($hand);
        return $hand;
    }

    function getBoardToJSON($state) {
        $board = $state["board"];
        //$jsonBoard = json_encode($board);
        return $board;
    }

	 function getEnd($state){
        return $state["end"];
    }


?>