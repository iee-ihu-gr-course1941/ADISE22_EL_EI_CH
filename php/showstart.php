<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (isset($_SESSION['status'])) {
    if ($_SESSION['status'] == 1) {
        echo $_SESSION['loginMessage'];
        ?>
        <form action="players_playing.php" method="POST">					
            <label for="newgame"><button class="button2">Νέο παιχνίδι</button></label><br/>
            <input type="submit" name="newgame" style="display:none">
        </form>
        <?php
        //active_players.php ../api.html
    } elseif ($_SESSION['status'] == 0) {
        echo $_SESSION['loginMessage'];
        ?>
        <a href="/register.html"> Εγγραφή στο παιχνίδι</a>
        <?php
    }
    exit;
} else {
    http_response_code(403);
    exit;
}
?>