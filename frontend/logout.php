<?php
session_start();
//Clears the session data
session_destroy();
//Redirects the user to login page
header("Location: login.php");
exit();
?>