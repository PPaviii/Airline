<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>BuyCheck</title>
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

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {

    $myReserved = $_SESSION["myReserved"];

    $_SESSION["myReserved"] = 0; //flush reserved seats

    $servername = "localhost";
    $username = "s264970";
    $password = "chalingt";

    $conn = new mysqli($servername, $username, $password, "s264970");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $reserved = "SELECT COUNT(*) AS Reserved FROM Seat WHERE Status = 0 AND Username = '" . $_SESSION["username"] . "'";
    $resRes = $conn->query($reserved);
    $rowRes = $resRes->fetch_assoc();
    $reservedMine = (int) $rowRes["Reserved"];
/*
    if($reservedMine === 0){
        echo "<script type='text/javascript'>";
        echo "window.alert('Please select at least one seat!');";
        echo "window.location.replace('personalPage.php');";
        echo "</script>";
        return;
    }
*/
    if($myReserved === $reservedMine){ //I purchase all my seats
        $update = "UPDATE Seat SET Status = 1 WHERE Username = '" . $_SESSION["username"] . "'";
        $conn->query($update);
        $conn->close();

        $_SESSION["ok"] = 1;

        header("HTTP/1.1 303 See Other");
        header("Location: personalPage.php");

/*
        echo "<script type='text/javascript'>";
        echo "window.alert('The seats were purchased correctly.');";
        echo "window.location.replace('personalPage.php');";
        echo "</script>";
*/
    }else{ //someone stole at leat one seat, I free all my seats

        $delete = "DELETE FROM Seat WHERE Username = '" . $_SESSION["username"] . "'";
        $conn->query($delete);
        $conn->close();

        $_SESSION["notok"] = 1;

        header("HTTP/1.1 303 See Other");
        header("Location: personalPage.php");
/*
        echo "<script type='text/javascript'>";
        echo "window.alert('The seats can't be purchased. Someone stolen you at least one seat!');";
        echo "window.location.replace('personalPage.php');";
        echo "</script>";
*/
    }

}else{
    echo "<h2>Session expired! Please log in and try again.</h2>";
    echo "<a href='login.php'>Log in</a>";
}

?>

</div>

</body>
</html>
