<!DOCTYPE html>
<html lang="en">
<head>
    <noscript>
        <style>div { display:none; }</style>
    </noscript>
    <meta charset="UTF-8">
    <title>RegisterCheck</title>
</head>
<body>

<div>

<?php

if (isset($_POST["user"]) && isset($_POST["pass1"]) && isset($_POST["pass2"])) {

    $servername = "localhost";
    $username = "s264970";
    $password = "chalingt";

    $conn = new mysqli($servername, $username, $password, "s264970");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST["user"];
    $password = password_hash($_POST["pass1"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO User (Username, Password) VALUES ('" . $username . "', '" . $password . "')";

    if ($conn->query($sql) === TRUE) {

        session_start();
        $_SESSION["logged"] = 1; //the user is now logged in
        $_SESSION["username"] = $_POST["user"];
        $_SESSION["active_time"] = time();

        $conn->close();
        echo "<script type='text/javascript'>";
        echo "window.alert('Registration completed successfully');";
        echo "window.location.replace('personalPage.php');";
        echo "</script>";
    }else if(mysqli_errno($conn) === 1062){
        $conn->close();
        echo "<script type='text/javascript'>";
        echo "window.alert('The username you inserted already exists. Try again.');";
        echo "window.location.replace('register.php');";
        echo "</script>";
    }else{
        $conn->close();
        echo "Error: <br>" . $conn->error;
        echo "<br><a href='register.php'>Return to the register page</a>";
    }

}else{
    echo "<h2>A problem has occurred</h2>";
    echo "<p>No change to the database were done.</p>";
    echo "<a href='register.php'>Return to the register page</a>";
}

?>

</div>
</body>
</html>
