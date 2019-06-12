<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        This page needs JavaScript activated to work.
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

require_once "phpFunctions.php";
require_once "Global.php";

enforceSSL();

start_secure_session();

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
    session_write_close();
    exit();
}

if(isset($_POST["lout"]) && $_POST["lout"] == 1){
    $_SESSION["logged"] = 0;
    $_SESSION["error"] = 0;
    destroy_secure_session();
    header("HTTP/1.1 303 See Other");
    header("Location: index.php");
    exit();
}

session_write_close();

/*
    status legend:
        - 0: reserved
        - 1: occupied
*/

$servername = "localhost";
$username = "s264970";
$password = "chalingt";

$conn = new mysqli($servername, $username, $password, "s264970");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<table>";

echo "</tr>";
for($i = 1; $i <= ROWS; $i++){
    echo"<tr>";
    for($x = ord('A'); $x < ord('A') + COLUMNS; $x++){
        $char = chr($x);
        echo "<td id='$i$char' style='background-color: limegreen' onclick='allert()'><img src='Images/seat.png' style='width:50px;height:50px;'>";
        echo $i . chr($x) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

$idReserved = "SELECT Seat FROM Seat WHERE Status = 0"; //id of all reserved seats
$idOccupied = "SELECT Seat FROM Seat WHERE Status = 1"; //id of all occupied seats

$resIdR = $conn->query($idReserved);
$resIdO = $conn->query($idOccupied);

while ($row = $resIdR->fetch_assoc()){
    echo "<script type='text/javascript'>";
    echo 'document.getElementById(\'' . $row["Seat"] . '\').style.background = "orange";';
    echo "</script>";
}

while ($row = $resIdO->fetch_assoc()){
    echo "<script type='text/javascript'>";
    echo 'document.getElementById(\'' . $row["Seat"] . '\').style.background = "red";';
    echo 'document.getElementById(\'' . $row["Seat"] . '\').onclick = "null"';
    echo "</script>";
}

$reserved = "SELECT COUNT(*) AS Reserved FROM Seat WHERE Status = 0";
$occupied = "SELECT COUNT(*) AS Occ FROM Seat WHERE Status = 1";
$total = ROWS * COLUMNS;

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

$conn->close();

?>

<script>

function allert(){
    window.alert('You must be logged in to purchase a seat. Log in and try again.');
    window.location.href = 'login.php';
}

</script>

</div>
</body>
</html>
