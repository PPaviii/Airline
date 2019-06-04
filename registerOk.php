<?php

session_start();

if (isset($_POST["user"]) && isset($_POST["pass"])) {
    $username = $_POST["user"];
    $password = password_hash($_POST["pass"], PASSWORD_BCRYPT);

    echo "<script type='text/javascript'>";
    echo "window.alert('Registration completed successfully');";
    echo "window.location = 'index.php';";
    echo "</script>";

}else{
    echo "<h2>A problem has occurred</h2>";
    echo "<p>No change to the database were done.</p>";
    echo "<a href='register.php'>Return to the register page</a>";
}

?>
