<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AirFra Home Page</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/style.css">
    <script src="Utility/jsFunctions.js"></script>
</head>
<body>

<noscript>
    <p>This page needs JavaScript activated to work.</p>
    <style>div { display:none; }</style>
</noscript>

<script>
    if(!navigator.cookieEnabled){
        document.write("<p>This page needs Cookies activated to work correctly.</p>");
        document.write("<style>div { display:none; }</style>");
    }
</script>

<div>

<?php

require_once "Utility/phpFunctions.php";
require_once "Utility/Global.php";

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
}elseif($_SESSION["logged"] == 1){
    header("HTTP/1.1 303 See Other");
    header("Location: personalPage.php");
    session_write_close();
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
    die("<br><br><p>An unexpected problem has occurred with the database connection. Please try again.</p>");
}

echo "<div id='nav'>";
echo "<nav>";
echo "<a class='active' href='index.php'>Home Page</a>";
echo "<a href='login.php'>Sign In</a>";
echo "<a href='register.php'>Sign Up</a>";
echo "<a style='opacity: 0.2; pointer-events: none' href='logout.php'>Log Out</a>";
echo "<a style='opacity: 0.2; pointer-events: none'>Update</a>";
echo "<a style='opacity: 0.2; pointer-events: none'>Buy</a>";
echo "</nav>";
echo "</div>";

printMapIndex();

$reserved = "SELECT COUNT(*) AS Reserved FROM Seat WHERE Status = 0";
$occupied = "SELECT COUNT(*) AS Occ FROM Seat WHERE Status = 1";
$total = ROWS * COLUMNS;

$resRes = $conn->query($reserved);
$resOcc = $conn->query($occupied);

$rowRes = $resRes->fetch_assoc();
$rowOcc = $resOcc->fetch_assoc();

if(!$resRes || !$resOcc){
    echo "<script>";
    echo "window.alert('There was an error in a query which collects statistics about seats. Please try again.');";
    echo "window.location.href = 'index.php';";
    echo "</script>";
    return;
}

if($rowRes == NULL || $rowOcc == NULL){
    echo "<script>";
    echo "window.alert('There was an error retrieving data from MySQLi object. Please try again.');";
    echo "window.location.href = 'index.php';";
    echo "</script>";
    return;
}

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
