<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Airline Home Page</title>
    <link rel="stylesheet" type="text/css" href="Stylesheets/table.css">
</head>
<body>

<h2>Airline Homepage</h2>

<?php

session_start();

$_SESSION["error"] = 0; //flush previous error in the login form

if(!isset($_SESSION["logged"])){
    $_SESSION["logged"] = 0;
}elseif ($_SESSION["logged"] == 1){
    header("HTTP/1.1 303 See Other");
    header("Location: personalPage.php");
}

if(isset($_POST["lout"]) && $_POST["lout"] == 1){
    $_SESSION["logged"] = 0;
    $_SESSION["error"] = 0;
    header("HTTP/1.1 303 See Other");
    header("Location: index.php");
}

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
        echo "<td><a href='login.php' onclick='allert()'>" . $i . chr($x) . "</a></td>";
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

echo "<a href='login.php'>Sign In</a>&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<a href='register.php'>Sign Up</a>";

?>

<script>

function allert(){
    window.alert('You must be logged in to purchase a seat. Log in and try again.');
}

</script>

</body>
</html>
