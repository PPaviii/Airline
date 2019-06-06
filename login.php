<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<h2>Sign In</h2>

<?php

session_start();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    echo "<h2>You are already logged in</h2>";
    echo "<a href='index.php'>Return to the Home page</a>";
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

    New to Airline Company? <a href="register.php">Sign Up!</a>

<?php

if (isset($_SESSION["error"]) && $_SESSION["error"] == 1) {
    echo "<p style='color: red'>Error: wrong username or password. Retry.</p><br>";
}

?>

</body>
</html>
