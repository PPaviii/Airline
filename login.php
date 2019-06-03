<?php

if($_SESSION["logged"] == 0) {

    if (!isset($_POST["user"]) && !isset($_POST["pass"])) {

        echo "<p>You must be logged in to purchase a seat. Insert your credentials in the form below.</p><br>";

        echo "<form action='buy.php' method='post'>";
        echo "<p>";
        echo "<label for=\"user\">Username:</label>";
        echo "<input type=\"text\" name=\"user\" id=\"name\" style=\"text-align: center\">";
        echo "</p><br>";

        echo "<p>";
        echo "<label for=\"pass\">Password:</label>";
        echo "<input type=\"password\" name=\"pass\" id=\"name\" style=\"text-align: center\">";
        echo "</p><br>";

        echo "<button type=\"submit\" name=\"submit\">Submit Credentials</button><br><br>";
        echo "</form>";

        if (isset($_SESSION["error"]) && $_SESSION["error"] == 1) {
            echo "<p style='color: red'>Error: wrong username or password. Retry.</p><br>";
        }

        return;

    } else {
        $log = "SELECT * FROM User WHERE Username = '" . $_POST["user"] . "' AND Password = '" . md5($_POST["pass"]) . "'";
        $res = $conn->query($log);
        $rows = mysqli_num_rows($res);

        if ($rows == 1) {
            $_SESSION["logged"] = 1;
            header("Location: index.php");
        } else {
            $_SESSION["error"] = 1;
            header("Location: buy.php");
        }
    }
}

?>
