<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/formReg.css">
    <script src="Utility/register.js"></script>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
</head>
<body>

<script>
    if(!navigator.cookieEnabled){
        document.write("<style>div { display:none; }</style>");
    }
</script>

<div>

<h2>Sign Up</h2>

<?php

require_once "Utility/phpFunctions.php";

enforceSSL();
start_secure_session();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

$_SESSION["active_time"] = time();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    echo "<script>";
    echo "window.alert('You are already registered and logged in.');";
    echo "window.location.href = 'personalPage.php';";
    echo "</script>";
    return;
}

session_write_close();

?>

    <div id='nav'>
        <nav>
            <a href='index.php'>Home Page</a>
            <a href='login.php'>Sign In</a>
            <a class="active" href='register.php' style='pointer-events: none;'>Sign Up</a>
            <a style='opacity: 0.2; pointer-events: none' href='logout.php'>Log Out</a>
            <a onclick='updateMap()' style='pointer-events: none; opacity: 0.2'>Update</a>
            <a id='buy' style='pointer-events: none; opacity: 0.2' href='buyOk.php'>Buy</a>
        </nav>
    </div>

<br><form id="register" action='registerOk.php' method='post' onsubmit="return check()">
    <p title="Insert a valid e-mail">
    <label for="user">E-mail:</label>
    <input type="email" name="user" id="user" placeholder="john@doe.us" style="text-align: center">
    </p><br>

    <p title="The password has to contain at least one lowercase letter and one character which is either uppercase or a digit">
    <label for="pass1">Password:</label>
    <input type="password" name="pass1" id="pass1" placeholder="********" style="text-align: center">
    </p><br>

    <p title="Please, insert again the selected password">
        <label for="pass2">Repeat Password:&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="password" name="pass2" id="pass2" placeholder="********" style="text-align: center">
    </p><br>

    <p>
        <label></label>
        <button title="Submit the form" type="submit" id="submit" name="submit">Sign Up</button><br><br>
    </p>
</form>

</div>

</body>
</html>
