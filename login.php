<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/formLog.css">
    <script>
        if(!navigator.cookieEnabled){
            document.write("<style>div { display:none; }</style>");
        }
    </script>
</head>
<body>

<div>

<h2>Sign In</h2>

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
    echo "window.alert('You are already logged in.');";
    echo "window.location.href = 'personalPage.php';";
    echo "</script>";
    return;
}

?>
    <div id='nav'>
        <ul>
            <li><a href='index.php'>Home Page</a></li>
            <li><a class="active" href='login.php' style='pointer-events: none'>Sign In</a></li>
            <li><a href='register.php'>Sign Up</a></li>
            <li style='pointer-events: none; opacity: 0.2'><a onclick='updateMap()'>Update Seats</a></li>
            <li id='buy' style='opacity: 0.2'><a id='buyl' style='pointer-events: none' href='buyOk.php'>Buy</a></li>
        </ul>
    </div>

<br><form id="login" action='loginOk.php' method='post'>
    <p title="Insert the e-mail you inserted in the registration phase">
        <label for="user">E-mail:</label>
        <input type="email" name="user" id="name" placeholder="john@doe.us" style="text-align: center">
    </p><br>

    <p title="Insert your secret password">
        <label for="pass">Password:&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="password" name="pass" id="pass" placeholder="********" style="text-align: center">
    </p><br>

    <p>
        <label></label>
    <button title="Submit the form" type="submit" name="submit" id="submit">Sign In</button><br><br>
    </p>
</form>

<?php

if (isset($_SESSION["error"]) && $_SESSION["error"] == 1) {
    echo "<br><br><p style='color: red'>Error: wrong username or password. Retry.</p><br>";
}

if (isset($_SESSION["notPresent"]) && $_SESSION["notPresent"] == 1) {
    echo "<br><br><p style='color: red'>Error: your username is not present in the database. Please Sign Up.</p><br>";
}

?>

</div>
</body>
</html>
