<?php

function isLoginSessionExpired() {
    $login_session_duration = 120;
    if(isset($_SESSION["username"])){
        if(((time() - $_SESSION["active_time"]) > $login_session_duration)){
            $_SESSION["logged"] = 0; //expired
        }else{
            $_SESSION["logged"] = 1; //not expired
        }
    }else{
        $_SESSION["logged"] = 0; //session not started yet
    }
}

?>
