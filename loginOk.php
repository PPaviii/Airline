<?php

session_start();

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

        $login = "SELECT Password FROM User WHERE Username = '" . $_POST["user"] . "'";

        $res = $conn->query($login);

        if (mysqli_num_rows($res) === 1){

            $nRow = $res->fetch_assoc();
            if (password_verify($_POST["pass"], $nRow["Password"])) {
                $_SESSION["logged"] = 1;
                $_SESSION["error"] = 0;
                header("HTTP/1.1 303 See Other");
                header("Location: PersonalPage.php");
            } else {
                $_SESSION["error"] = 1;
                header("HTTP/1.1 303 See Other");
                header("Location: login.php");
            }
        }else{
            $_SESSION["error"] = 1;
            header("HTTP/1.1 303 See Other");
            header("Location: login.php");
        }
    }

}else {
    echo "<h2>You are already logged in</h2>";
    echo "<a href='index.php'>Return to the Home page</a>";
}

?>
