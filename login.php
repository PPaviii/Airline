<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/form.css">
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
    echo "<p>You are already logged in</p><br>";
    echo "<a href='personalPage.php'>Return to the Personal Home page</a>";
    return;
}

?>

<form id="login" action='loginOk.php' method='post'>
    <p>
        <label for="user">E-mail:</label>
        <input type="email" name="user" id="name" placeholder="john@doe.us" style="text-align: center">
    </p><br>

    <p>
        <label for="pass">Password:&nbsp;&nbsp;</label>
        <input type="password" name="pass" id="pass" placeholder="********" style="text-align: center">
    </p><br>

    <button type="submit" name="submit">Sign In</button><br><br>
</form>

    New to AirFra Company? <a href="register.php">Sign Up!</a><br><br>

    <a href="index.php">Return to the seats map</a>
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
