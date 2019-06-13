<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>AirFra Personal Home Page</title>
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
$_SESSION["notPresent"] = 0;
$_SESSION["myReserved"] = 0; //flush reserved counter

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

printMapPersonalPage();

$reserved = "SELECT COUNT(*) AS Reserved FROM Seat WHERE Status = 0";
$reservedMe = "SELECT COUNT(*) AS ReservedMe FROM Seat WHERE Status = 0 AND Username = '" . $_SESSION["username"] . "'";
$occupied = "SELECT COUNT(*) AS Occ FROM Seat WHERE Status = 1";
$total = ROWS * COLUMNS;

$resRes = $conn->query($reserved);
$resResMe = $conn->query($reservedMe);
$resOcc = $conn->query($occupied);

$rowRes = $resRes->fetch_assoc();
$rowResMe = $resResMe->fetch_assoc();
$rowOcc = $resOcc->fetch_assoc();

$reservedTotMe = (int) $rowResMe["ReservedMe"];
$reservedTot = (int) $rowRes["Reserved"];
$occupiedTot = (int) $rowOcc["Occ"];
$free = $total - $reservedTot - $occupiedTot;

echo "<p id='free'>Number of available seats: " . $free . "</p>";
echo "<p id='reserved'>Number of reserved seats: " . $reservedTot . "</p>";
echo "<p id='reservedMe'>Number of seats you have reserved: " . $reservedTotMe . "</p>";
echo "<p id='occupied'>Number of occupied seats: " . $occupiedTot . "</p>";
echo "<p>Total number of seats: $total</p>";

echo "<form action='index.php' method='post'>";
echo "<p style='color:green'>Hi, " . $_SESSION["username"] . ".</p>";
echo "<p style='color: green'>Now you are logged in and you can purchase airplane seats.</p>";
echo "<input type=\"hidden\" value=\"1\" name=\"lout\">";
echo "<button type=\"submit\" name=\"logout\">Log Out</button>";
echo "</form><br>";

session_write_close();

echo "<button onclick='updateMap()'>Update Seats</button><br><br>";

echo "<form method='post' action='buyOk.php'>";
echo "<button type=\"submit\" name=\"buy\" id=\"buy\" style='display: none'>Buy!</button>";
echo "</form>";

$anyMine = "SELECT COUNT(*) AS Mine FROM Seat WHERE Status = 0 AND Username = '" . $_SESSION["username"] . "'";
$resMine = $conn->query($anyMine);
$rowMine = $resMine->fetch_assoc();

$mine = (int) $rowMine["Mine"];

if($mine > 0){
    echo "<script>";
    echo "document.getElementById(\"buy\").style.display = \"block\";";
    echo "</script>";
}

$conn->close();

if(isset($_SESSION["ok"]) && $_SESSION["ok"] == 1){
    $_SESSION["ok"] = 0;
    unset($_SESSION["ok"]);
    echo "<script type='text/javascript'>";
    echo "window.alert('The seats were purchased correctly.');";
    echo "window.location.href = 'index.php';";
    echo "</script>";
}

if(isset($_SESSION["notok"]) && $_SESSION["notok"] == 1){
    $_SESSION["notok"] = 0;
    unset($_SESSION["notok"]);
    echo "<script type='text/javascript'>";
    echo "window.alert('Purchase cannot be completed, someone stolen you at least one seat!');";
    echo "window.location.href = 'index.php';";
    echo "</script>";
}

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

                        var free = document.getElementById("free").innerHTML;
                        var valueF = parseInt(free.replace(/[^0-9\.]/g, ''), 10);
                        valueF -= 1;
                        document.getElementById("free").innerHTML = "Number of available seats: " + valueF;

                        var reserved = document.getElementById("reserved").innerHTML;
                        var valueR = parseInt(reserved.replace(/[^0-9\.]/g, ''), 10);
                        valueR += 1;
                        document.getElementById("reserved").innerHTML = "Number of reserved seats: " + valueR;

                        var reservedMe = document.getElementById("reservedMe").innerHTML;
                        var valueRMe = parseInt(reservedMe.replace(/[^0-9\.]/g, ''), 10);
                        valueRMe += 1;
                        document.getElementById("reservedMe").innerHTML = "Number of seats you have reserved: " + valueRMe;

                        if(valueRMe > 0){
                            document.getElementById("buy").style.display = "block";
                        }

                        document.getElementById(id).style.background = "yellow";
                        break;

                    case "InputError":
                        window.alert('You sent an invalid seat id. Retry');
                        break;

                    case "UNDO":
                        window.alert("Reservation deleted successfully!");

                        var free2 = document.getElementById("free").innerHTML;
                        var valueF2 = parseInt(free2.replace(/[^0-9\.]/g, ''), 10);
                        valueF2 += 1;
                        document.getElementById("free").innerHTML = "Number of available seats: " + valueF2;

                        var reserved2 = document.getElementById("reserved").innerHTML;
                        var valueR2 = parseInt(reserved2.replace(/[^0-9\.]/g, ''), 10);
                        valueR2 -= 1;
                        document.getElementById("reserved").innerHTML = "Number of reserved seats: " + valueR2;

                        var reservedMe2 = document.getElementById("reservedMe").innerHTML;
                        var valueRMe2 = parseInt(reservedMe2.replace(/[^0-9\.]/g, ''), 10);
                        valueRMe2 -= 1;
                        document.getElementById("reservedMe").innerHTML = "Number of seats you have reserved: " + valueRMe2;

                        if(valueRMe2 === 0){
                            document.getElementById("buy").style.display = "none";
                        }

                        document.getElementById(id).style.background = "limegreen";
                        break;

                    case "Purchased":
                        window.alert("Sorry, the seat has already been purchased.");

                        var free3 = document.getElementById("free").innerHTML;
                        var valueF3 = parseInt(free3.replace(/[^0-9\.]/g, ''), 10);
                        valueF3 -= 1;
                        document.getElementById("free").innerHTML = "Number of available seats: " + valueF3;

                        var reserved3 = document.getElementById("reserved").innerHTML;
                        var valueR3 = parseInt(reserved3.replace(/[^0-9\.]/g, ''), 10);
                        valueR3 -= 1;
                        document.getElementById("reserved").innerHTML = "Number of reserved seats: " + valueR3;

                        var reservedMe3 = document.getElementById("reservedMe").innerHTML;
                        var valueRMe3 = parseInt(reservedMe3.replace(/[^0-9\.]/g, ''), 10);
                        valueRMe3 -= 1;
                        document.getElementById("reservedMe").innerHTML = "Number of seats you have reserved: " + valueRMe3;

                        var occupied = document.getElementById("occupied").innerHTML;
                        var valueO = parseInt(occupied.replace(/[^0-9\.]/g, ''), 10);
                        valueO += 1;
                        document.getElementById("occupied").innerHTML = "Number of occupied seats: " + valueO;

                        document.getElementById(id).style.background = "red";
                        document.getElementById(id).onclick = "null";
                        break;

                    case "NOT-OK":
                        window.alert('Session expired. Log in and try again.');
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
    
    function updateMap() {

        if (window.XMLHttpRequest) {
            // code for modern browsers
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for old IE browsers
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if(this.responseText === "NOT-OK"){
                    window.alert('Session expired. Log in and try again.');
                    window.location.href = 'login.php';
                }else {
                    var colors = this.responseText.split(" ");
                    var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");

                    for(var j = 1; j <= colors[0]; j++){ //colors[0] = rows
                        for(var f = 0; f < colors[1]; f++) { //colors[1] = columns
                            document.getElementById(j + alphabet[f]).style.background = "limegreen";
                        }
                    }

                    for(var i = 2; i < colors.length -1; i++){
                        document.getElementById(colors[i]).style.background = colors[++i];
                    }

                }
            }
        };

        xmlhttp.open("POST", "update.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send();
    }
</script>

</div>
</body>
</html>
