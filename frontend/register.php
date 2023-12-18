<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Connects to the database
    include 'db_connect.php';

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //Hashes the password

    //SQL to insert new user into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    //Prepares and bind
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $mysqli->error);
    }

    $stmt->bind_param("sss", $username, $email, $password);

    //Executes and redirects the user to the login page or shows an error
    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" href="App.css">
</head>
<body>
    <div class="container">
    <h2>Register</h2>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required placeholder="Enter your username">

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required placeholder="Enter your email">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required placeholder="Enter your password">

            <input type="submit" value="Register">

            <div class="login">
                <p>Already have an account? <a href="login.php">Login Here</a>.</p>
            </div>
        </form>
    </div>
</body>