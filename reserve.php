<?php

require_once "phpFunctions.php";
enforceSSL();

start_secure_session();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1) {
    isLoginSessionExpired();
}

$_SESSION["active_time"] = time();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == 1){

    $id = $_POST["seatId"];
    $id = strip_tags($id);
    $id = htmlentities($id);

    if($id !== $_POST["seatId"]){
        echo "InputError";
        return;
    }

    $servername = "localhost";
    $username = "s264970";
    $password = "chalingt";

    $conn = new mysqli($servername, $username, $password, "s264970");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $check = "SELECT Username, Status FROM Seat WHERE Seat = '" . $id . "'";
    $resCheck = $conn->query($check);

    $result = $resCheck->fetch_assoc();

    if($result != NULL){
        if($result["Username"] == $_SESSION["username"]){

            $tmp = $_SESSION["myReserved"];
            $tmp -= 1;
            $_SESSION["myReserved"] = $tmp;

            $delete = "DELETE FROM Seat WHERE Seat = '" . $id . "'";
            $conn->query($delete);
            $conn->close();

            echo "UNDO";
            return;
        }else{
            if($result["Status"] == 0){ //I steal the reservation
                $update = "UPDATE Seat SET Username = '" . $_SESSION["username"] . "' WHERE Seat = '" . $id . "'";
                $conn->query($update);
                $conn->close();

                $tmp = $_SESSION["myReserved"];
                $tmp += 1;
                $_SESSION["myReserved"] = $tmp;

                echo "OK";
                return;
            }else{ //seat has been purchased
                $conn->close();
                echo "Purchased";
                return;
            }

        }
    }

    $insert = "INSERT INTO Seat (Seat, Status, Username) VALUES ('" . $id ."', 0, '" . $_SESSION["username"] . "')";

    $tmp = $_SESSION["myReserved"];
    $tmp += 1;
    $_SESSION["myReserved"] = $tmp;

    $conn->query($insert);
    $conn->close();

    echo "OK";
}else{
    echo "NOT-OK";
}

?>
