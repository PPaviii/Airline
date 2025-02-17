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

require_once "Utility/phpFunctions.php";

enforceSSL();
start_secure_session();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

$_SESSION["active_time"] = time();

if(!isset($_SESSION["logged"]) || $_SESSION["logged"] == 0) {

    if (isset($_POST["user"]) && isset($_POST["pass1"]) && isset($_POST["pass2"])) {

        $user = strip_tags($_POST["user"]);
        $user = htmlentities($user);
        $pass = strip_tags($_POST["pass1"]);
        $pass = htmlentities($pass);

        if ($user !== $_POST["user"] || $pass !== $_POST["pass1"]) {
            echo "<script type='text/javascript'>";
            echo "window.alert('There was an error with your input values. Please try again.');";
            echo "window.location.href = 'register.php';";
            echo "</script>";
            return;
        }

        if (!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $user)) {
            echo "<script type='text/javascript'>";
            echo "window.alert('Error: please insert a valid e-mail.');";
            echo "window.location.replace('register.php');";
            echo "</script>";
            return;
        }

        if (!preg_match('/^(([a-z]*)+([A-Z]|\d)+[a-z]+)|(([a-z]+)+([A-Z]|\d)+([a-z]*))$/', $pass)) {
            echo "<script type='text/javascript'>";
            echo "window.alert('Error: please insert a valid password.');";
            echo "window.location.replace('register.php');";
            echo "</script>";
            return;
        }

        $servername = "localhost";
        $username = "s264970";
        $password = "chalingt";

        $conn = new mysqli($servername, $username, $password, "s264970");

        if ($conn->connect_error) {
            die("<br><br><p>An unexpected problem has occurred with the database connection. Please try again.</p>");
        }

        $passwordH = password_hash($pass, PASSWORD_DEFAULT);

        $insert = $conn->prepare("INSERT INTO User (Username, Password) VALUES (?, ?)");

        if(!$insert){
            echo "<script type='text/javascript'>";
            echo "window.alert('An unexpected problem has occurred with the prepare statement. Please try again.');";
            echo "window.location.href = 'register.php';";
            echo "</script>";
            return;
        }

        if(!$insert->bind_param("ss", $user, $passwordH)){
            echo "<script type='text/javascript'>";
            echo "window.alert('An unexpected problem has occurred with the bind_param statement. Please try again.');";
            echo "window.location.href = 'register.php';";
            echo "</script>";
            return;
        }

        if ($insert->execute() === TRUE) {

            $_SESSION["logged"] = 1; //the user is now logged in
            $_SESSION["username"] = $user;
            $_SESSION["active_time"] = time();

            session_regenerate_id();
            session_write_close();
            $insert->close();
            $conn->close();

            echo "<script type='text/javascript'>";
            echo "window.alert('Registration completed successfully');";
            echo "window.location.replace('personalPage.php');";
            echo "</script>";
        } else if ($insert->errno === 1062) {
            $insert->close();
            $conn->close();

            $_SESSION["error"] = 0;
            session_write_close();

            echo "<script type='text/javascript'>";
            echo "window.alert('The username you inserted already exists. Please Log In.');";
            echo "window.location.replace('login.php');";
            echo "</script>";
        } else {
            session_write_close();
            $insert->close();
            $conn->close();

            echo "<script type='text/javascript'>";
            echo "window.alert('An unexpected problem has occurred. Please try again.');";
            echo "window.location.href = 'register.php';";
            echo "</script>";
            return;
        }

    } else {
        session_write_close();
        echo "<h2>A problem has occurred: no input values. You are not registered.</h2>";
        echo "<p>No change to the database were done.</p>";
        echo "<a href='register.php'>Return to the register page</a>";
    }

}else{
    echo "<script type='text/javascript'>";
    echo "window.alert('You are already registered and logged in.');";
    echo "window.location.href = 'personalPage.php';";
    echo "</script>";
}

?>

</div>
</body>
</html>
