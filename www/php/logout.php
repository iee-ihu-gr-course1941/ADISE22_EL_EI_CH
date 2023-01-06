<?php

if (!isset($connected) || $connected == false) {
    require "connection_with_db.php";
}
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
session_destroy();

session_write_close();
header("Location: https://users.it.teithe.gr/~it185421/adise22/ADISE22_EL_EI_CH/www/html/index.html");
exit;
?>