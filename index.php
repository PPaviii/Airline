<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        This page needs JavaScript activated to work.
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>AirFra Home Page</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/style.css">
    <script type="text/javascript" src="jsFunctions.js"></script>
    <script>
        if(!navigator.cookieEnabled){
            document.write("<p>This page needs Cookies activated to work correctly.</p>");
            document.write("<style>div { display:none; }</style>");
        }
    </script>
</head>
<body>

<div>

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
$_SESSION["notPresent"] = 0;
$_SESSION["ok"] = 0; //flush variables to print alert when purchasing
$_SESSION["notok"] = 0;

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
    $_SESSION["notPresent"] = 0;
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

echo "<div id='nav'>";
echo "<ul>";
echo "<li><a class='active' href='index.php'>Home Page</a></li>";
echo "<li><a href='login.php'>Sign In</a></li>";
echo "<li><a href='register.php'>Sign Up</a></li>";
echo "<li><a style='opacity: 0.2; pointer-events: none'>Update Seats</a></li>";
echo "<li><a style='opacity: 0.2; pointer-events: none'>Buy</a></li>";
echo "</ul>";
echo "</div>";

printMapIndex();

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

echo "<div id='info'>";
echo "<p style='margin-top: 91%; color: limegreen'>Number of free seats: " . $free . "</p>";
echo "<p style='color: orange'>Number of reserved seats: " . $reserved . "</p>";
echo "<p style='color: red'>Number of occupied seats: " . $occupied . "</p>";
echo "<p>Total number of seats: $total</p>";
echo "</div>";

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
