<?php

function start_secure_session(){
    $session_name = "sec_session";
    ini_set("session.use_only_cookies", 1);
    $params = session_get_cookie_params();
    session_set_cookie_params($params["lifetime"], $params["path"], $params["domain"], TRUE, TRUE);
    session_name($session_name);
    session_start();
}

function destroy_secure_session(){
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600*24,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]);
    }
    session_destroy();
}

function isLoginSessionExpired() {
    $login_session_duration = 120;
    if(isset($_SESSION["username"])){
        if(((time() - $_SESSION["active_time"]) > $login_session_duration)){
            $_SESSION["logged"] = 0; //expired
            destroy_secure_session();
        }else{
            $_SESSION["logged"] = 1; //not expired
        }
    }else{
        $_SESSION["logged"] = 0; //session not started yet
    }
}

function enforceSSL(){
    if(empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on"){
        header("HTTP/1.1 303 See Other");
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        exit();
    }
}

?>
