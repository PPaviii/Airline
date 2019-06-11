<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>RegisterCheck</title>
    <script>
        if(!navigator.cookieEnabled){
            document.write("<style>div { display:none; }</style>");
        }
    </script>
</head>
<body>

<div>

<?php

require_once "phpFunctions.php";
enforceSSL();

session_start();

if (isset($_POST["user"]) && isset($_POST["pass1"]) && isset($_POST["pass2"])) {

    $user = strip_tags($_POST["user"]);
    $user = htmlentities($user);
    $pass = strip_tags($_POST["pass1"]);
    $pass = htmlentities($pass);

    if($user !== $_POST["user"] || $pass !== $_POST["pass1"]){
        echo "<h2>A problem has occurred with your input values. You registration is not valid.</h2>";
        echo "<p>No change to the database were done.</p>";
        echo "<a href='register.php'>Return to the register page</a>";
        return;
    }

    if (!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $user)){
        echo "<script type='text/javascript'>";
        echo "window.alert('Error: please insert a valid e-mail.');";
        echo "window.location.replace('register.php');";
        echo "</script>";
    }

    if(!preg_match('/^(([a-z]*)+([A-Z]|\d)+[a-z]+)|(([a-z]+)+([A-Z]|\d)+([a-z]*))$/', $pass)){
        echo "<script type='text/javascript'>";
        echo "window.alert('Error: please insert a valid password.');";
        echo "window.location.replace('register.php');";
        echo "</script>";
    }

    $servername = "localhost";
    $username = "s264970";
    $password = "chalingt";

    $conn = new mysqli($servername, $username, $password, "s264970");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $passwordH = password_hash($pass, PASSWORD_DEFAULT);

    $insert = $conn->prepare("INSERT INTO User (Username, Password) VALUES (?, ?)");
    $insert->bind_param("ss", $user, $passwordH);

    if ($insert->execute() === TRUE) {

        $_SESSION["logged"] = 1; //the user is now logged in
        $_SESSION["username"] = $user;
        $_SESSION["active_time"] = time();

        session_write_close();
        $insert->close();
        $conn->close();

        echo "<script type='text/javascript'>";
        echo "window.alert('Registration completed successfully');";
        echo "window.location.replace('personalPage.php');";
        echo "</script>";
    }else if($insert->errno === 1062){
        session_write_close();
        $insert->close();
        $conn->close();

        echo "<script type='text/javascript'>";
        echo "window.alert('The username you inserted already exists. Try again.');";
        echo "window.location.replace('register.php');";
        echo "</script>";
    }else{
        session_write_close();
        $insert->close();
        $conn->close();

        echo "Unexpected error.<br>";
        echo "<br><a href='register.php'>Return to the register page</a>";
    }

}else{
    session_write_close();
    echo "<h2>A problem has occurred</h2>";
    echo "<p>No change to the database were done.</p>";
    echo "<a href='register.php'>Return to the register page</a>";
}

?>

</div>
</body>
</html>
