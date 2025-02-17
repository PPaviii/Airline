<?php

function start_secure_session(){
    $session_name = "sec_session";
    ini_set("session.use_only_cookies", 1);
    $params = session_get_cookie_params();
    session_set_cookie_params($params["lifetime"], $params["path"], $params["domain"], TRUE, TRUE);
    session_name($session_name);
    session_start();
}

function destroy_secure_session(){
    $_SESSION = array();
    session_regenerate_id();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600*24,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]);
    }
    session_destroy();
}

function isLoginSessionExpired() {
    $login_session_duration = 120;
    if(isset($_SESSION["username"])){
        if(((time() - $_SESSION["active_time"]) > $login_session_duration)){
            $_SESSION["logged"] = 0; //expired
            destroy_secure_session();
        }else{
            $_SESSION["logged"] = 1; //not expired
        }
    }else{
        $_SESSION["logged"] = 0; //session not started yet
    }
}

function enforceSSL(){
    if(empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] !== "on"){
        header("HTTP/1.1 303 See Other");
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        exit();
    }
}

function printMapIndex(){

    $servername = "localhost";
    $username = "s264970";
    $password = "chalingt";

    $conn = new mysqli($servername, $username, $password, "s264970");

    if ($conn->connect_error) {
        die("<br><br><p>An unexpected problem has occurred with the database connection. Please try again.</p>");
    }

    echo "<div id='map'>";

    echo "<h2>AirFra Home Page</h2>";

    echo "<table>";

    for($i = 1; $i <= ROWS; $i++){
        echo"<tr>";
        for($x = ord('A'); $x < ord('A') + COLUMNS; $x++){
            $char = chr($x);
            echo "<td id='$char$i' onmouseover='darker(this.id, this.style.backgroundColor)' onmouseout='normal(this.id)' style='background-color: limegreen' onclick='allert()'><img src='Images/seat.png' alt='Seat' style='width:50px;height:50px;'>";
            echo chr($x) . $i . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";

    $idReserved = "SELECT Seat FROM Seat WHERE Status = 0"; //id of all reserved seats
    $idOccupied = "SELECT Seat FROM Seat WHERE Status = 1"; //id of all occupied seats

    $resIdR = $conn->query($idReserved);
    $resIdO = $conn->query($idOccupied);

    if(!$resIdR || !$resIdO){
        echo "<script>";
        echo "window.alert('There was an error in a query which collects statistics about seats. Please try again.');";
        echo "window.location.href = 'index.php';";
        echo "</script>";
        return;
    }

    while ($row = $resIdR->fetch_assoc()){

        if($row == NULL){
            echo "<script>";
            echo "window.alert('There was an error retrieving data from MySQLi object. Please try again.');";
            echo "window.location.href = 'index.php';";
            echo "</script>";
            return;
        }

        echo "<script>";
        echo 'document.getElementById(\'' . $row["Seat"] . '\').style.background = "orange";';
        echo "</script>";
    }

    while ($row = $resIdO->fetch_assoc()){

        if($row == NULL){
            echo "<script>";
            echo "window.alert('There was an error retrieving data from MySQLi object. Please try again.');";
            echo "window.location.href = 'index.php';";
            echo "</script>";
            return;
        }

        echo "<script>";
        echo 'document.getElementById(\'' . $row["Seat"] . '\').style.background = "red";';
        echo 'document.getElementById(\'' . $row["Seat"] . '\').onclick = "null"';
        echo "</script>";
    }

    $conn->close();
}

function printMapPersonalPage(){

    $servername = "localhost";
    $username = "s264970";
    $password = "chalingt";

    $conn = new mysqli($servername, $username, $password, "s264970");

    if ($conn->connect_error) {
        die("<br><br><p>An unexpected problem has occurred with the database connection. Please try again.</p>");
    }

    echo "<div id='map'>";

    echo "<h2>AirFra Personal Home Page</h2>";

    echo "<table id='seatmap'>";

    for($i = 1; $i <= ROWS; $i++){
        echo"<tr>";
        for($x = ord('A'); $x < ord('A') + COLUMNS; $x++){
            $char = chr($x);
            echo "<td id='$char$i' onmouseover='darker(this.id, this.style.backgroundColor)' onmouseout='normal(this.id)' onclick='reserveSeat(this.id)' style='background-color: limegreen'><img src='Images/seat.png' alt='Seat' style='width:50px;height:50px;'>";
            echo chr($x). $i . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";

    $idReserved = "SELECT Username, Seat FROM Seat WHERE Status = 0"; //id of all reserved seats
    $idOccupied = "SELECT Seat FROM Seat WHERE Status = 1"; //id of all occupied seats

    $resIdR = $conn->query($idReserved);
    $resIdO = $conn->query($idOccupied);

    if(!$resIdR || !$resIdO){
        echo "<script>";
        echo "window.alert('There was an error in a query which collects statistics about seats. Please try again.');";
        echo "window.location.href = 'personalPage.php';";
        echo "</script>";
        return;
    }

    while ($row = $resIdR->fetch_assoc()){

        if($row == NULL){
            echo "<script>";
            echo "window.alert('There was an error retrieving data from MySQLi object. Please try again.');";
            echo "window.location.href = 'personalPage.php';";
            echo "</script>";
            return;
        }

        if($_SESSION["username"] == $row["Username"]){
            $tmp = $_SESSION["myReserved"];
            $tmp += 1;
            $_SESSION["myReserved"] = $tmp;
            echo "<script>";
            echo "document.getElementById(\"" . $row["Seat"] . "\").style.background = \"yellow\";";
            echo "</script>";
        }else {
            echo "<script>";
            echo "document.getElementById(\"" . $row["Seat"] . "\").style.background = \"orange\";";
            echo "</script>";
        }
    }

    while ($row = $resIdO->fetch_assoc()){

        if($row == NULL){
            echo "<script>";
            echo "window.alert('There was an error retrieving data from MySQLi object. Please try again.');";
            echo "window.location.href = 'personalPage.php';";
            echo "</script>";
            return;
        }

        echo "<script>";
        echo "document.getElementById(\"" . $row["Seat"] . "\").style.background = \"red\";";
        echo "document.getElementById(\"" . $row["Seat"] . "\").onclick = \"null\";";
        echo "</script>";
    }

}

function updateColors(){

    $servername = "localhost";
    $username = "s264970";
    $password = "chalingt";

    $conn = new mysqli($servername, $username, $password, "s264970");

    if ($conn->connect_error) {
        die("<br><br><p>An unexpected problem has occurred with the database connection. Please try again.</p>");
    }

    $idReserved = "SELECT Username, Seat FROM Seat WHERE Status = 0"; //id of all reserved seats
    $idOccupied = "SELECT Seat FROM Seat WHERE Status = 1"; //id of all occupied seats

    $resIdR = $conn->query($idReserved);
    $resIdO = $conn->query($idOccupied);

    if(!$resIdR || !$resIdO){
        echo "<script>";
        echo "window.alert('There was an error in a query which collects statistics about seats. Please try again.');";
        echo "window.location.href = 'personalPage.php';";
        echo "</script>";
        return;
    }

    $seatNcolors = "";
    $seatNcolors .= ROWS . " " . COLUMNS . " ";

    $mine = 0;
    $reserved = 0;
    $occupied = 0;

    while ($row = $resIdR->fetch_assoc()){

        if($row == NULL){
            echo "<script>";
            echo "window.alert('There was an error retrieving data from MySQLi object. Please try again.');";
            echo "window.location.href = 'personalPage.php';";
            echo "</script>";
            return;
        }

        if($_SESSION["username"] == $row["Username"]){
            $mine += 1;
            $seatNcolors .= $row["Seat"] . " ";
            $seatNcolors .= "yellow ";
        }else {
            $reserved += 1;
            $seatNcolors .= $row["Seat"] . " ";
            $seatNcolors .= "orange ";
        }
    }

    while ($row = $resIdO->fetch_assoc()){

        if($row == NULL){
            echo "<script>";
            echo "window.alert('There was an error retrieving data from MySQLi object. Please try again.');";
            echo "window.location.href = 'personalPage.php';";
            echo "</script>";
            return;
        }

        $occupied += 1;
        $seatNcolors .= $row["Seat"] . " ";
        $seatNcolors .= "red ";
    }


    $free = ROWS * COLUMNS - $reserved - $occupied - $mine;

    $seatNcolors .= $mine . " "  . $reserved . " " . $occupied . " " . $free;
    echo $seatNcolors;
}

?>
