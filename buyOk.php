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

    if($myReserved == 0){
        echo "<script type='text/javascript'>";
        echo "window.alert('Please, select at least one seat.');";
        echo "window.location.href = 'personalPage.php';";
        echo "</script>";
        return;
    }

    $_SESSION["myReserved"] = 0; //flush reserved seats

    $servername = "localhost";
    $username = "s264970";
    $password = "chalingt";

    $conn = new mysqli($servername, $username, $password, "s264970");

    if ($conn->connect_error) {
        die("<br><br><p>An unexpected problem has occurred with the database connection. Please try again.</p>");
    }

    mysqli_autocommit($conn, false);

    $reserved = "SELECT COUNT(*) AS Reserved FROM Seat WHERE Status = 0 AND Username = '" . $_SESSION["username"] . "' FOR UPDATE";
    $resRes = $conn->query($reserved);

    if(!$resRes){
        echo "<script type='text/javascript'>";
        echo "window.alert('There was an error in a query which collects statistics about seats. Please try again.');";
        echo "window.location.href = 'personalPage.php';";
        echo "</script>";
        return;
    }

    $rowRes = $resRes->fetch_assoc();

    if($rowRes == NULL){
        echo "<script type='text/javascript'>";
        echo "window.alert('There was an error retrieving data from MySQLi object. Please try again.');";
        echo "window.location.href = 'personalPage.php';";
        echo "</script>";
        return;
    }

    $reservedMine = (int) $rowRes["Reserved"];

    if($myReserved === $reservedMine){ //I purchase all my seats
        $update = "UPDATE Seat SET Status = 1 WHERE Username = '" . $_SESSION["username"] . "'";

        if(!$conn->query($update)){
            echo "<script type='text/javascript'>";
            echo "window.alert('There was an error updating the database. No changes were done. Please try again.');";
            echo "window.location.href = 'personalPage.php';";
            echo "</script>";
            return;
        }

        mysqli_commit($conn);
        $conn->close();

        $_SESSION["ok"] = 1;

        header("HTTP/1.1 303 See Other");
        header("Location: personalPage.php");
    }else{ //someone stole at least one seat, I free all my reserved seats

        $delete = "DELETE FROM Seat WHERE Status = 0 AND Username = '" . $_SESSION["username"] . "'";

        if(!$conn->query($delete)){
            echo "<script type='text/javascript'>";
            echo "window.alert('There was an error updating the database. No changes were done. Please try again.');";
            echo "window.location.href = 'personalPage.php';";
            echo "</script>";
            return;
        }

        mysqli_commit($conn);
        $conn->close();

        $_SESSION["notok"] = 1;

        header("HTTP/1.1 303 See Other");
        header("Location: personalPage.php");
    }

}else{
    echo "<script type='text/javascript'>";
    echo "window.alert('You are not authenticated to the system. Log in and try again.');";
    echo "window.location.href = 'login.php';";
    echo "</script>";
}

?>

</div>

</body>
</html>
