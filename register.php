<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/form.css">
    <script type="text/javascript" src="register.js"></script>
</head>
<body>

<h2>Sign Up</h2>

<?php

include "phpFunctions.php";

session_start();

isLoginSessionExpired();

$_SESSION["active_time"] = time();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    echo "<p>You are already registered</p><br>";
    echo "<a href='personalPage.php'>Return to the Personal Home page</a>";
    return;
}

?>

<form id="register" action='registerOk.php' method='post' onsubmit="return check();">
    <p>
    <label for="user">E-mail:</label>
    <input type="email" name="user" id="name" placeholder="john@doe.us" style="text-align: center">
    </p><br>

    <p>
    <label for="pass">Password:</label>
    <input type="password" name="pass1" id="pass1" placeholder="********" style="text-align: center">
    </p><br>

    <p>
        <label for="pass">Repeat Password:&nbsp;&nbsp;</label>
        <input type="password" name="pass2" id="pass2" placeholder="********" style="text-align: center">
    </p><br>

    <button type="submit" name="submit">Sign Up</button><br><br>
</form>

</body>
</html>
