<?php

require_once "Utility/phpFunctions.php";
require_once "Utility/Global.php";

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

    if(!preg_match('/^[A-Z]\d+$/', $id)){
        echo "InputError";
        return;
    }else{
        $num = intval(preg_replace('/[^0-9]+/', '', $id), 10);
        if($num >= 1 && $num <= ROWS){
            $char = strval(preg_replace('/[0-9]+/', '', $id));
            if(ord($char) >= ord("A") && ord($char) < (ord('A') + COLUMNS)){
                //do nothing, go on
            }else{
                echo "InputError";
                return;
            }
        }else{
            echo "InputError";
            return;
        }
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
        echo "<script type='text/javascript'>";
        echo "window.alert('There was an error in a query which checks seat availability. Please try again.');";
        echo "window.location.href = 'personalPage.php';";
        echo "</script>";
        return;
    }

    $result = $resCheck->fetch_assoc();

    if($result != NULL){
        if($result["Username"] == $_SESSION["username"]){

            $tmp = $_SESSION["myReserved"];
            $tmp -= 1;
            $_SESSION["myReserved"] = $tmp;

            $delete = "DELETE FROM Seat WHERE Seat = '" . $id . "'";

            if(!$conn->query($delete)){
                echo "<script type='text/javascript'>";
                echo "window.alert('There was an error updating the database. No changes were done. Please try again.');";
                echo "window.location.href = 'personalPage.php';";
                echo "</script>";
                return;
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
                    echo "<script type='text/javascript'>";
                    echo "window.alert('There was an error updating the database. No changes were done. Please try again.');";
                    echo "window.location.href = 'personalPage.php';";
                    echo "</script>";
                    return;
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
        echo "<script type='text/javascript'>";
        echo "window.alert('There was an error updating the database. No changes were done. Please try again.');";
        echo "window.location.href = 'personalPage.php';";
        echo "</script>";
        return;
    }

    mysqli_commit($conn);
    $conn->close();

    echo "OK";
}else{
    echo "NOT-OK";
}

?>
