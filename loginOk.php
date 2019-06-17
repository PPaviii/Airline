<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>LoginCheck</title>
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

start_secure_session();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

$_SESSION["active_time"] = time();

if(!isset($_SESSION["logged"]) || $_SESSION["logged"] == 0) {

    if (!isset($_POST["user"]) && !isset($_POST["pass"])) {
        echo "<h2>A problem has occurred</h2>";
        echo "<a href='index.php'>Return to the home page</a>";
    }else {

        $servername = "localhost";
        $username = "s264970";
        $password = "chalingt";

        $conn = new mysqli($servername, $username, $password, "s264970");

        if ($conn->connect_error) {
            die("<br><br><p>An unexpected problem has occurred with the database connection. Please try again.</p>");
        }

        $user = strip_tags($_POST["user"]);
        $user = htmlentities($user);
        $pass = strip_tags($_POST["pass"]);
        $pass = htmlentities($pass);

        if($user !== $_POST["user"] || $pass !== $_POST["pass"]){
            echo "<h2>A problem has occurred: no input values. You are not logged in.</h2>";
            echo "<a href='login.php'>Return to the log in page</a>";
            return;
        }

        $login = $conn->prepare("SELECT Password FROM User WHERE Username = ?");

        if(!$login){
            echo "<script type='text/javascript'>";
            echo "window.alert('An unexpected problem has occurred with the prepare statement. Please try again.');";
            echo "window.location.href = 'login.php';";
            echo "</script>";
            return;
        }

        if(!$login->bind_param("s", $user)){
            echo "<script type='text/javascript'>";
            echo "window.alert('An unexpected problem has occurred with the bind_param statement. Please try again.');";
            echo "window.location.href = 'login.php';";
            echo "</script>";
            return;
        }

        if(!$login->execute()){
            echo "<script type='text/javascript'>";
            echo "window.alert('An unexpected problem has occurred with the execute statement. Please try again.');";
            echo "window.location.href = 'login.php';";
            echo "</script>";
            return;
        }

        if(!$login->store_result()){
            echo "<script type='text/javascript'>";
            echo "window.alert('An unexpected problem has occurred with the store_result statement. Please try again.');";
            echo "window.location.href = 'login.php';";
            echo "</script>";
            return;
        }

        $rows = $login->num_rows;

        if(!$login->bind_result($password)){
            echo "<script type='text/javascript'>";
            echo "window.alert('An unexpected problem has occurred with the bind_result statement. Please try again.');";
            echo "window.location.href = 'login.php';";
            echo "</script>";
            return;
        }

        $login->fetch();

        if ($rows === 1){

            if (password_verify($pass, $password)) {
                $_SESSION["logged"] = 1;
                $_SESSION["error"] = 0;

                $_SESSION["username"] = $user;
                $_SESSION["active_time"] = time();

                session_regenerate_id();
                session_write_close();
                $login->close();
                $conn->close();

                header("HTTP/1.1 303 See Other");
                header("Location: personalPage.php");
                exit();
            } else {
                $_SESSION["error"] = 1;

                session_write_close();
                $login->close();
                $conn->close();

                header("HTTP/1.1 303 See Other");
                header("Location: login.php");
                exit();
            }
        }else{
            $_SESSION["error"] = 0;
            $_SESSION["notPresent"] = 1;

            session_write_close();
            $login->close();
            $conn->close();

            header("HTTP/1.1 303 See Other");
            header("Location: login.php");
            exit();
        }
    }

}else {
    echo "<script type='text/javascript'>";
    echo "window.alert('You are already logged in.');";
    echo "window.location.href = 'personalPage.php';";
    echo "</script>";
}

?>

</div>

</body>
</html>
