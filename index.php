<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Airline Home Page</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<h2>Airline Homepage</h2>

<?php

/*
    status legend:
        - 0: free
        - 1: reserved
        - 2: occupied
*/

//instance varibles
$rows = 10; //places
$columns = 6; //seats
//end instance

//db
$servername = "localhost";
$username = "s264970";
$password = "chalingt";

$conn = new mysqli($servername, $username, $password, "s264970");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//end db


//table of seats
echo "<table>";
echo "<tr>";
echo "<th></th>";

for ($x = ord('A'); $x < ord('A') + $columns; $x++) {
    echo "<th>" . chr($x) ."</th>";
}

echo "</tr>";
for($i = 1; $i <= $rows; $i++){
    echo"<tr>";
    echo "<th>$i</th>";
    for($j = 1; $j <= $columns; $j++){
        echo "<td>Posto</td>";
    }
    echo "</tr>";
}
echo "</table>";
//end table

//queries for seat statistics
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
//end queries

echo "<br><p>Number of available seats: " . $rowFree["Free"] . "</p>";
echo "<br><p>Number of reserved seats: " . $rowRes["Reserved"] . "</p>";
echo "<br><p>Number of occupied seats: " . $rowOcc["Occ"] . "</p>";
echo "<br><p>Total number of seats: $total</p>";

?>

</body>
</html>
