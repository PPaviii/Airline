<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>AirFra Personal Home Page</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/style.css">
    <script type="text/javascript" src="jsFunctions.js"></script>
    <script>
        if(!navigator.cookieEnabled){
            document.write("<style>div { display:none; }</style>");
        }
    </script>
</head>
<body>

<div id="main">

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
    die("<br><br><p>An unexpected problem has occurred with the database connection. Please try again.</p>");
}

echo "<div id='nav'>";
echo "<ul>";
echo "<li><a class='active' href='index.php'>Home Page</a></li>";
echo "<li><a href='login.php' style='pointer-events: none; opacity: 0.2'>Sign In</a></li>";
echo "<li><a href='register.php' style='pointer-events: none; opacity: 0.2'>Sign Up</a></li>";
echo "<li><a href='logout.php'>Log Out</a></li>";
echo "<li><a title='Refresh the seats map completely' onclick='updateMap()'>Update Seats</a></li>";
echo "<li id='buy' style='opacity: 0.2'><a id='buyl' style='pointer-events: none' href='buyOk.php'>Buy</a></li>";
echo "</ul>";
echo "</div>";

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

if(!$resRes || !$resResMe || !$resOcc){
    echo "<script type='text/javascript'>";
    echo "window.alert('There was an error in a query which collects statistics about seats. Please try again.');";
    echo "window.location.href = 'personalPage.php';";
    echo "</script>";
    return;
}

if($rowRes == NULL || $rowResMe == NULL || $rowOcc == NULL){
    echo "<script type='text/javascript'>";
    echo "window.alert('There was an error retrieving data from MySQLi object. Please try again.');";
    echo "window.location.href = 'personalPage.php';";
    echo "</script>";
    return;
}

$reservedTotMe = (int) $rowResMe["ReservedMe"];
$reservedTot = (int) $rowRes["Reserved"];
$occupiedTot = (int) $rowOcc["Occ"];
$free = $total - $reservedTot - $occupiedTot;

echo "<div id='info'>";
echo "<p style='color:green'>Hi, " . $_SESSION["username"] . "</p>";
echo "<p style='color: green'>Now you are logged in and you can purchase airplane seats. <br> To reserve a seat just click the one you prefer.</p>";

echo "<p id='free' style='margin-top: 45%; color: limegreen'>Number of free seats: " . $free . "</p>";
echo "<p id='reserved' style='color: orange;'>Number of seats others have reserved: " . ($reservedTot - $reservedTotMe) . "</p>";
echo "<p id='reservedMe' style='color: yellow'>Number of seats you have reserved: " . $reservedTotMe . "</p>";
echo "<p id='occupied' style='color: red'>Number of occupied seats: " . $occupiedTot . "</p>";
echo "<p>Total number of seats: $total</p>";
echo "</div>";

session_write_close();

$anyMine = "SELECT COUNT(*) AS Mine FROM Seat WHERE Status = 0 AND Username = '" . $_SESSION["username"] . "'";
$resMine = $conn->query($anyMine);
$rowMine = $resMine->fetch_assoc();

if(!$resMine || $rowMine == NULL){
    echo "<script type='text/javascript'>";
    echo "window.alert('There was an error in a query which collects statistics about your seats. Please try again.');";
    echo "window.location.href = 'personalPage.php';";
    echo "</script>";
    return;
}

$mine = (int) $rowMine["Mine"];

if($mine > 0){
    echo "<script type='text/javascript'>";
    echo "document.getElementById(\"buy\").style.opacity = \"1\";";
    echo "document.getElementById(\"buyl\").style.pointerEvents = \"visible\";";
    echo "</script>";
}

$conn->close();

if(isset($_SESSION["ok"]) && $_SESSION["ok"] == 1){
    echo "<script type='text/javascript'>";
    echo "window.alert('The seats were purchased correctly.');";
    echo "window.location.href = 'index.php';";
    echo "</script>";
}

if(isset($_SESSION["notok"]) && $_SESSION["notok"] == 1){
    echo "<script type='text/javascript'>";
    echo "window.alert('Purchase cannot be completed, someone stolen you at least one seat!');";
    echo "window.location.href = 'index.php';";
    echo "</script>";
}

?>

<script>
    function reserveSeat(id){

        var seatColor = document.getElementById(id).style.backgroundColor;

        if (window.XMLHttpRequest) {
            // code for modern browsers
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for old IE browsers
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                //window.alert(this.responseText);

                switch (this.responseText) {
                    case "OK":

                        if(seatColor === "yellow"){
                            document.getElementById(id).style.background = "limegreen";
                            break;
                        }

                        window.alert("Reservation inserted successfully!");

                        if(seatColor === "orange"){
                            var reserved = document.getElementById("reserved").innerHTML;
                            var valueR = parseInt(reserved.replace(/[^0-9\.]/g, ''), 10);
                            valueR -= 1;
                            document.getElementById("reserved").innerHTML = "Number of seats others have reserved: " + valueR;
                        }else {
                            var free = document.getElementById("free").innerHTML;
                            var valueF = parseInt(free.replace(/[^0-9\.]/g, ''), 10);
                            valueF -= 1;
                            document.getElementById("free").innerHTML = "Number of free seats: " + valueF;
                        }

                        var reservedMe = document.getElementById("reservedMe").innerHTML;
                        var valueRMe = parseInt(reservedMe.replace(/[^0-9\.]/g, ''), 10);
                        valueRMe += 1;
                        document.getElementById("reservedMe").innerHTML = "Number of seats you have reserved: " + valueRMe;

                        if(valueRMe > 0){
                            document.getElementById("buy").style.opacity = "1";
                            document.getElementById("buyl").style.pointerEvents = "visible";
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
                        document.getElementById("free").innerHTML = "Number of free seats: " + valueF2;

                        var reservedMe2 = document.getElementById("reservedMe").innerHTML;
                        var valueRMe2 = parseInt(reservedMe2.replace(/[^0-9\.]/g, ''), 10);
                        valueRMe2 -= 1;
                        document.getElementById("reservedMe").innerHTML = "Number of seats you have reserved: " + valueRMe2;

                        if(valueRMe2 === 0){
                            document.getElementById("buy").style.opacity = "0.2";
                            document.getElementById("buyl").style.pointerEvents = "none";
                        }

                        document.getElementById(id).style.background = "limegreen";
                        break;

                    case "Purchased":
                        window.alert("Sorry, the seat has already been purchased.");

                        var free3 = document.getElementById("free").innerHTML;
                        var valueF3 = parseInt(free3.replace(/[^0-9\.]/g, ''), 10);
                        valueF3 -= 1;
                        document.getElementById("free").innerHTML = "Number of free seats: " + valueF3;

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
        xmlhttp.send("seatId=" + id + "&color=" + seatColor);
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

                    for(var i = 2; i <= colors.length - 5; i++){
                        document.getElementById(colors[i]).style.background = colors[++i];
                    }

                    document.getElementById("free").innerHTML = "Number of free seats: " + colors[colors.length - 1];
                    document.getElementById("reserved").innerHTML = "Number of seats others have reserved: " + colors[colors.length - 3];
                    document.getElementById("reservedMe").innerHTML = "Number of seats you have reserved: " + colors[colors.length - 4];
                    document.getElementById("occupied").innerHTML = "Number of occupied seats: " + colors[colors.length - 2];
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
