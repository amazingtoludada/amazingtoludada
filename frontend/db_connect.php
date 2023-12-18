<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "database";

//Creates a connection to the database
$mysqli = new mysqli($servername, $username, $password, $dbname);

//Checks the connection to the database
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>