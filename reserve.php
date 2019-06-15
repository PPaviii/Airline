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
        die("<br><br><p>An unexpected problem has occurred with the database connection. Please try again.</p>");
    }

    mysqli_autocommit($conn, false);

    $check = "SELECT Username, Status FROM Seat WHERE Seat = '" . $id . "' FOR UPDATE";
    $resCheck = $conn->query($check);

    if(!$resCheck){
        die("<br><br><p>There was an error in a query which checks seat availability. Please try again.</p>");
    }

    $result = $resCheck->fetch_assoc();

    if($result != NULL){
        if($result["Username"] == $_SESSION["username"]){

            $tmp = $_SESSION["myReserved"];
            $tmp -= 1;
            $_SESSION["myReserved"] = $tmp;

            $delete = "DELETE FROM Seat WHERE Seat = '" . $id . "'";

            if(!$conn->query($delete)){
                die("<br><br><p>There was an error updating the databse. No changes were done. Please try again.</p>");
            }

            mysqli_commit($conn);
            $conn->close();

            echo "UNDO";
            return;
        }else{
            if($result["Status"] == 0){ //I steal the reservation

                if($_POST["color"] === "yellow"){ //undo in case of two users reserve the same seat and then the first one deletes his reservation
                    mysqli_commit($conn);
                    $conn->close();
                    echo "OK";
                    return;
                }

                $update = "UPDATE Seat SET Username = '" . $_SESSION["username"] . "' WHERE Seat = '" . $id . "'";

                if(!$conn->query($update)){
                    die("<br><br><p>There was an error updating the databse. No changes were done. Please try again.</p>");
                }

                mysqli_commit($conn);
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

    if(!$conn->query($insert)){
        die("<br><br><p>There was an error updating the databse. No changes were done. Please try again.</p>");
    }

    mysqli_commit($conn);
    $conn->close();

    echo "OK";
}else{
    echo "NOT-OK";
}

?>
