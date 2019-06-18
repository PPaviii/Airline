<?php

require_once "Utility/phpFunctions.php";
start_secure_session();
destroy_secure_session();

header("HTTP/1.1 303 See Other");
header("Location: index.php");
exit();

?>
