<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>Airline Personal Home Page</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/table.css">
    <script>
        if(!navigator.cookieEnabled){
            document.write("<style>div { display:none; }</style>");
        }
    </script>
</head>
<body>

<div>

<h2>AirFra Personal Homepage</h2>

<?php

require_once "phpFunctions.php";
require_once "Global.php";
enforceSSL();

start_secure_session();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

$_SESSION["active_time"] = time();

if(!isset($_SESSION["logged"]) || $_SESSION["logged"] == 0){
    $_SESSION["logged"] = 0;
    session_write_close();
    header("HTTP/1.1 303 See Other");
    header("Location: login.php");
    exit();
}

$_SESSION["error"] = 0; //flush previous error in the login form

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
        echo "<td id='$i$char' onclick='reserveSeat(this.id)' style='background-color: limegreen'><img src='Images/seat.png' style='width:50px;height:50px;'>";
        echo $i . chr($x) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

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

echo "<form action='index.php' method='post'>";
echo "<p style='color:green'>Hi, " . $_SESSION["username"] . ".</p>";
echo "<p style='color: green'>Now you are logged in and you can purchase airplane seats.</p>";
echo "<input type=\"hidden\" value=\"1\" name=\"lout\">";
echo "<button type=\"submit\" name=\"logout\">Log Out</button>";
echo "</form><br>";

echo "<button onclick='window.location.reload()'>Update Seats</button>";

echo "<form method='post' action='buyOk.php'>";
echo "<button type=\"submit\" name=\"buy\">Buy!</button>";
echo "</form>";

$idReserved = "SELECT Username, Seat FROM Seat WHERE Status = 0"; //id of all reserved seats
$idOccupied = "SELECT Seat FROM Seat WHERE Status = 1"; //id of all occupied seats

$resIdR = $conn->query($idReserved);
$resIdO = $conn->query($idOccupied);

while ($row = $resIdR->fetch_assoc()){
    if($_SESSION["username"] == $row["Username"]){
        echo "<script type='text/javascript'>";
        echo 'document.getElementById(\'' . $row["Seat"] . '\').style.background = "yellow";';
        echo "</script>";
    }else {
        echo "<script type='text/javascript'>";
        echo 'document.getElementById(\'' . $row["Seat"] . '\').style.background = "orange";';
        echo "</script>";
    }
}

session_write_close();

while ($row = $resIdO->fetch_assoc()){
    echo "<script type='text/javascript'>";
    echo 'document.getElementById(\'' . $row["Seat"] . '\').style.background = "red";';
    echo 'document.getElementById(\'' . $row["Seat"] . '\').onclick = "null"';
    echo "</script>";
}

$conn->close();

?>

<script>
    function reserveSeat(id){
        if (window.XMLHttpRequest) {
            // code for modern browsers
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for old IE browsers
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                switch (this.responseText) {
                    case "OK":
                        window.alert("Reservation inserted successfully!");
                        document.getElementById(id).style.background = "yellow";
                        break;
                    case "InputError":
                        window.alert('You sent an invalid seat id. Retry');
                        break;
                    case "UNDO":
                        window.alert("Reservation deleted successfully!");
                        document.getElementById(id).style.background = "limegreen";
                        break;
                    case "Purchased":
                        window.alert("Sorry, the seat has already been purchased.");
                        document.getElementById(id).style.background = "red";
                        document.getElementById(id).onclick = "null";
                        break;
                    case "NOT-OK":
                        window.alert('You must be logged in to purchase a seat. Log in and try again.');
                        window.location.href = 'login.php';
                        break;
                    default:
                        window.alert('Unexpected error. Try Again');
                        break;
                }
            }
        };

        xmlhttp.open("POST", "reserve.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("seatId=" + id);
    }
</script>

</div>
</body>
</html>
