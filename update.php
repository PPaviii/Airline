<?php

require_once "phpFunctions.php";
require_once "Global.php";

enforceSSL();
start_secure_session();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

$_SESSION["active_time"] = time();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1){
    updateColors();
}else {
    echo "NOT-OK";
}

?>
