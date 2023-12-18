<?php
    session_start();

    //Checks to see if the "user" session variable is set
    if (!isset($_SESSION['id'])) {
        echo "Error: Session variable 'user' is not set. Please log in.";
        exit();
    }

    //Assumes you're connected to the database and the user_id is stored in a session variable
    $user_id = $_SESSION['username'];

    //Connects to the sql database
    $mysqli = new mysqli("localhost", "root", "mysql", "database");

    //Retrieves the schedule data from the database
    $sql = "SELECT * FROM schedules WHERE user_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    //Checks if the form was submitted and if the delete schedule button was clicked
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'Delete') {
        $id = $_POST['id'];
    
        $sql = "DELETE FROM schedules WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    //Checks if the form was submitted and if the update schedule button was clicked
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'Update') {
        $id = $_POST['id'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $details = $_POST['details'];
    
        $sql = "UPDATE schedules SET date = ?, time = ?, details = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssi", $date, $time, $details, $id);
        $stmt->execute();
    }

    //Checks to see if the form was submitted and if the add schedule button was clicked
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'Add') {
        $date = $_POST['date'];
        $time = $_POST['time'];
        $details = $_POST['details'];

        $sql = "INSERT INTO schedules (user_id, date, time, details) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("isss", $user_id, $date, $time, $details);
        $stmt->execute();
    }

?>



<!DOCTYPE html>
<html>
<head>
    <title>Your Schedule</title>
    <link rel="stylesheet" href="App.css">
</head>
<body>
    <div class="container">

        <!-- Form to add/update schedule -->
    <div class="form-container">
    <h2>Add Events to Schedule</h2>  
        <form action="" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="id" value="<?php echo $schedule['id']; ?>">
            Date: <input type="date" id="date" name="date"><br>
            Time: <input type="time" id="time" name="time"><br>
            Details: <textarea name="details"></textarea><br>
            <input type="submit" name="action" value="Add">
        </form>
    </div>

    <h2>Your Schedule</h2>
    <table border="1" class="wide-table">
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Details</th>
            <th>Actions</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($schedule = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $schedule['date']; ?></td>
                    <td><?php echo $schedule['time']; ?></td>
                    <td><?php echo $schedule['details']; ?></td>
                    <td>
                        <a href="edit_schedule.php?id=<?php echo $schedule['id']; ?>">Edit</a>
                        <a href="delete_schedule.php?id=<?php echo $schedule['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No schedule data found for this user.</td>
                </tr>
            <?php endif; ?>
        </table>

        <br/>
        <br/>
        <div class="navbar">
            <a href="logout.php">Logout</a>
        </div>
        <br/>
    </div>  
</body>