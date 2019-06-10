<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <meta http-equiv="refresh" content="0; URL=/MyProject/Airline/Errors/errorJs.php">
    </noscript>
    <meta charset="UTF-8">
    <title>LoginCheck</title>
    <script>
        if(!navigator.cookieEnabled){
            document.write("<meta http-equiv='refresh' content='0; URL=/MyProject/Airline/Errors/errorCookies.php'>");
        }
    </script>
</head>
<body>

<div>

<?php

include "phpFunctions.php";
enforceSSL();

session_start();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

$_SESSION["active_time"] = time();

if($_SESSION["logged"] == 0) {

    if (!isset($_POST["user"]) && !isset($_POST["pass"])) {
        echo "<h2>A problem has occurred</h2>";
        echo "<a href='index.php'>Return to the home page</a>";
    }else {

        $servername = "localhost";
        $username = "s264970";
        $password = "chalingt";

        $conn = new mysqli($servername, $username, $password, "s264970");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $user = strip_tags($_POST["user"]);
        $user = htmlentities($user);
        $pass = strip_tags($_POST["pass"]);
        $pass = htmlentities($pass);

        $login = $conn->prepare("SELECT Password FROM User WHERE Username = ?");
        $login->bind_param("s", $user);
        $login->execute();
        $login->store_result();
        $rows = $login->num_rows;
        $login->bind_result($password);
        $login->fetch();

        if ($rows === 1){

            if (password_verify($pass, $password)) {
                $_SESSION["logged"] = 1;
                $_SESSION["error"] = 0;

                $_SESSION["username"] = $user;
                $_SESSION["active_time"] = time();

                $login->close();
                $conn->close();

                header("HTTP/1.1 303 See Other");
                header("Location: personalPage.php");
                exit();
            } else {
                $_SESSION["error"] = 1;

                $login->close();
                $conn->close();

                header("HTTP/1.1 303 See Other");
                header("Location: login.php");
                exit();
            }
        }else{
            $_SESSION["error"] = 1;

            $login->close();
            $conn->close();

            header("HTTP/1.1 303 See Other");
            header("Location: login.php");
            exit();
        }
    }

}else {
    echo "<h2>You are already logged in</h2>";
    echo "<a href='index.php'>Return to the Home page</a>";
}

?>

</div>

</body>
</html>
