<?php

require_once "phpFunctions.php";
enforceSSL();

start_secure_session();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

if($_SESSION["logged"] == 1){
    echo "OK";
}else{
    echo "NOT_OK";
}

?>
