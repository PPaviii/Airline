<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/formReg.css">
    <script type="text/javascript" src="register.js"></script>
    <script>
        if(!navigator.cookieEnabled){
            document.write("<style>div { display:none; }</style>");
        }
    </script>
</head>
<body>

<div>

<h2>Sign Up</h2>

<?php

require_once "phpFunctions.php";
enforceSSL();

start_secure_session();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

$_SESSION["active_time"] = time();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    echo "<script type='text/javascript'>";
    echo "window.alert('You are already registered and logged in.');";
    echo "window.location.href = 'personalPage.php';";
    echo "</script>";
    return;
}

session_write_close();

?>

    <div id='nav'>
        <ul>
            <li><a href='index.php'>Home Page</a></li>
            <li><a href='login.php'>Sign In</a></li>
            <li><a class="active" href='register.php' style='pointer-events: none;'>Sign Up</a></li>
            <li style='pointer-events: none; opacity: 0.2'><a onclick='updateMap()'>Update Seats</a></li>
            <li id='buy' style='opacity: 0.2'><a id='buyl' style='pointer-events: none' href='buyOk.php'>Buy</a></li>
        </ul>
    </div>

<br><form id="register" action='registerOk.php' method='post' onsubmit="return check();">
    <p title="Insert a valid e-mail">
    <label for="user">E-mail:</label>
    <input type="email" name="user" id="name" placeholder="john@doe.us" style="text-align: center">
    </p><br>

    <p title="The password has to contain at least one lowercase letter and one character which is either uppercase or a digit">
    <label for="pass">Password:</label>
    <input type="password" name="pass1" id="pass1" placeholder="********" style="text-align: center">
    </p><br>

    <p title="Please, insert again the selected password">
        <label for="pass">Repeat Password:&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="password" name="pass2" id="pass2" placeholder="********" style="text-align: center">
    </p><br>

    <p>
        <label></label>
    <button title="Submit the form" type="submit" id="submit "name="submit">Sign Up</button><br><br>
    </p>
</form>

</div>

</body>
</html>
