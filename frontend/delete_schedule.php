<?php
    session_start();

    //Checks to see if the user session variable is set
    if (!isset($_SESSION['id'])) {
        echo "Error: Session variable 'user' is not set. Please log in.";
        exit();
    }

    //Assumes you have a connection to the database and the user_id is stored in a session variable
    $user_id = $_SESSION['username'];

    //Connects to the sql database
    $mysqli = new mysqli("localhost", "root", "mysql", "database");

    //Retrieves the schedule data from the database
    $id = $_GET['id'];

    //Deletes the schedule data from the database
    $sql = "DELETE FROM schedules WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

?>

<head>
    <link rel="stylesheet" href="App.css">
    <body>
        <div class="delete-container">
            <h1>Schedule Data Deleted</h1>
        <br/>

            <div class="navbar">
                <a href="schedule.php">Back</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </body>
</head>