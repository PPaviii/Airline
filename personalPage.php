<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <meta http-equiv="refresh" content="0; URL=/MyProject/Airline/Errors/errorJsIndex.php">
    </noscript>
    <meta charset="UTF-8">
    <title>Airline Personal Home Page</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/table.css">
    <script>
        if(!navigator.cookieEnabled){
            document.write("<meta http-equiv='refresh' content='0; URL=/MyProject/Airline/Errors/errorCookiesIndex.php'>");
        }
    </script>
</head>
<body>

<div>

<h2>AirFra Personal Homepage</h2>

<?php

include "phpFunctions.php";
enforceSSL();

session_start();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

$_SESSION["active_time"] = time();

if(!isset($_SESSION["logged"]) || $_SESSION["logged"] == 0){
    $_SESSION["logged"] = 0;
    header("HTTP/1.1 303 See Other");
    header("Location: login.php");
    exit();
}

$_SESSION["error"] = 0; //flush previous error in the login form

/*
    status legend:
        - 0: free
        - 1: reserved
        - 2: occupied
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
        $char = chr($x);
        echo "<td id='$i$char' onclick='colorChange(this.id)' style='background-color: limegreen'><img src='Images/seat.png' style='width:50px;height:50px;'>";
        echo $i . chr($x) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

$free = "SELECT COUNT(*) AS Free FROM Seat WHERE Status = 0";
$reserved = "SELECT COUNT(*) AS Reserved FROM Seat WHERE Status = 1";
$occupied = "SELECT COUNT(*) AS Occ FROM Seat WHERE Status = 2";
$total = $rows * $columns;

$resFree = $conn->query($free);
$resRes = $conn->query($reserved);
$resOcc = $conn->query($occupied);

$rowFree = $resFree->fetch_assoc();
$rowRes = $resRes->fetch_assoc();
$rowOcc = $resOcc->fetch_assoc();

echo "<p>Number of available seats: " . $rowFree["Free"] . "</p>";
echo "<p>Number of reserved seats: " . $rowRes["Reserved"] . "</p>";
echo "<p>Number of occupied seats: " . $rowOcc["Occ"] . "</p>";
echo "<p>Total number of seats: $total</p>";

echo "<form action='index.php' method='post'>";
echo "<p style='color: green'>Now you are logged in and you can purchase airplane seats.</p>";
echo "<input type=\"hidden\" value=\"1\" name=\"lout\">";
echo "<button type=\"submit\" name=\"logout\">Log Out</button>";
echo "</form><br>";

echo "<form method='post'>";
echo "<button type=\"submit\" name=\"update\" formaction='personalPage.php'>Update Seats</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
echo "<button type=\"submit\" name=\"buy\">Buy!</button>";
echo "</form>";

$idReserved = "SELECT Seat FROM Seat WHERE Status = 0"; //id of all reserved seats
$idOccupied = "SELECT Seat FROM Seat WHERE Status = 1"; //id of all occupied seats

$resIdR = $conn->query($idReserved);
$resIdO = $conn->query($idOccupied);

while ($row = $resIdR->fetch_assoc()){
    echo "<script type='text/javascript'>";
    echo 'document.getElementById(\'' . $row["Seat"] . '\').style.background = "red";';
    echo 'document.getElementById(\'' . $row["Seat"] . '\').onclick = "null"';
    echo "</script>";
}

while ($row = $resIdO->fetch_assoc()){
    echo "<script type='text/javascript'>";
    echo 'document.getElementById(\'' . $row["Seat"] . '\').style.background = "orange";';
    echo "</script>";
}

?>

<script>
    function colorChange(id){
        document.getElementById(id).style.background = "yellow";
    }
</script>

</div>
</body>
</html>

