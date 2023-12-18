<?php
    session_start();

    //Checks to see if the "user" session variable is set
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
    $sql = "SELECT * FROM schedules WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedule = $result->fetch_assoc();

    //Updates the schedule data in the database
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $details = $_POST['details'];

        $sql = "UPDATE schedules SET date = ?, time = ?, details = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssi", $date, $time, $details, $id);
        $stmt->execute();
    }

?>

<head>
    <link rel="stylesheet" href="App.css">
    <body>
        <div class="edit-container">
            <h1>Edit Schedule</h1>
            
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $schedule['id']; ?>">
            Date: <input type="date" id="date" name="date" value="<?php echo $schedule['date']; ?>"><br>
            Time: <input type="time" id="time" name="time" value="<?php echo $schedule['time']; ?>"><br>
            Details: <textarea name="details"><?php echo $schedule['details']; ?></textarea><br>
            <input type="submit" name="submit" value="Update">
        </form>

            <div class="navbar">
                <a href="schedule.php">Back</a>
                <a href="logout.php">Logout</a>
            </div>
            <br/>
            <br/>
        </div> 
    </body>
</head>