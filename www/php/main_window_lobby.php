<?php
require "connection_with_db.php";
require "libraries.php";
require "state-of_db.php";

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$json_output = array();
if (check_active_game() == true) {
    $json_output['goto_url'] = 'api.html';
}
echo json_encode($json_output);

session_write_close();
exit;
?>