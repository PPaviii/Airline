<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Airline Home Page</title>
    <style>
        table {width: 25%; height: auto;}
        table, th, td {border: 1px solid black;}
        th, td {text-align: center;}
        th, td {padding: 5px;}
        th {background-color: lightgrey}
    </style>
</head>
<body>

<h2>Airline Homepage</h2>

<?php

$rows = 10; //places
$columns = 6; //seats

$servername = "localhost";
$username = "s264970";
$password = "chalingt";

// Create connection
$conn = new mysqli($servername, $username, $password, "s264970");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

echo "<p>Number of available seats:</p>";
echo "<p>Number of occupied seats:</p>";
echo "<p>Total number of seats:</p>";

?>
