<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        This page needs JavaScript activated to work correctly.
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>Airline Home Page</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/table.css">
    <script>
        if(!navigator.cookieEnabled){
            document.write("<p>This page needs Cookies activated to work correctly.</p>");
            document.write("<style>div { display:none; }</style>");
        }
    </script>
</head>
<body>

<div id="div">

<h2>AirFra Homepage</h2>

<?php

include "phpFunctions.php";
enforceSSL();

session_start();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

$_SESSION["active_time"] = time();

$_SESSION["error"] = 0; //flush previous error in the login form

if(!isset($_SESSION["logged"])){
    $_SESSION["logged"] = 0;
}elseif($_SESSION["logged"] == 1 && !isset($_POST["lout"])){
    header("HTTP/1.1 303 See Other");
    header("Location: personalPage.php");
    exit();
}

if(isset($_POST["lout"]) && $_POST["lout"] == 1){
    $_SESSION["logged"] = 0;
    $_SESSION["error"] = 0;
    session_destroy();
    header("HTTP/1.1 303 See Other");
    header("Location: index.php");
    exit();
}

/*
    status legend:
        - 0: reserved
        - 1: occupied
*/

$rows = 10; //places
$columns = 6; //seats

$servername = "localhost";
$username = "s264970";
$password = "chalingt";

$conn = new mysqli($servername, $username, $password, "s264970");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<table>";

echo "</tr>";
for($i = 1; $i <= $rows; $i++){
    echo"<tr>";
    for($x = ord('A'); $x < ord('A') + $columns; $x++){
        echo "<td><a href='login.php' onclick='allert()'>" . $i . chr($x) . "</a></td>";
    }
    echo "</tr>";
}
echo "</table>";

$reserved = "SELECT COUNT(*) AS Reserved FROM Seat WHERE Status = 0";
$occupied = "SELECT COUNT(*) AS Occ FROM Seat WHERE Status = 1";
$total = $rows * $columns;

$resRes = $conn->query($reserved);
$resOcc = $conn->query($occupied);

$rowRes = $resRes->fetch_assoc();
$rowOcc = $resOcc->fetch_assoc();

$reserved = (int) $rowRes["Reserved"];
$occupied = (int) $rowOcc["Occ"];
$free = $total - $reserved - $occupied;

echo "<p>Number of available seats: " . $free . "</p>";
echo "<p>Number of reserved seats: " . $reserved . "</p>";
echo "<p>Number of occupied seats: " . $occupied . "</p>";
echo "<p>Total number of seats: $total</p>";

echo "<a href='login.php'>Sign In</a>&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<a href='register.php'>Sign Up</a>";

?>

<script>

function allert(){
    window.alert('You must be logged in to purchase a seat. Log in and try again.');
}

</script>

</div>
</body>
</html>
