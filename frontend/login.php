<?php
//Starts the session
session_start();

//Checks to see if the user is already logged in, if yes then redirect them to the home page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: schedule.php");
    exit;
}

//Includes database configuration file
include('db_connect.php');

//Defines variables and initializes with empty values
$username = $password = "";
$username_err = $password_err = "";

//Processes the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Checks to see if there's any username entered
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } else {
        $username = trim($_POST["username"]);
    }

    //Checks to see if there's any password entered
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    //Validate credentials
    if (empty($username_err) && empty($password_err)) {
        //Prepares a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            //Binds variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            //Sets parameters
            $param_username = $username;

            //Attempts to execute the prepared statement
            if ($stmt->execute()) {
                //Stores result of the statement
                $stmt->store_result();

                //Checks to see if the username exists in the database, and asks the user to enter their password if it does
                if ($stmt->num_rows == 1) {
                    //Binds the result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            //Starts a new session if the user's password is entered correctly
                            session_start();

                            //Stores data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            //Redirects the user to the schedule page
                            header("location: schedule.php");
                        } else {
                            //Displays an error message if the user's password is entered incorrectly
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    //Displays an error message if the username doesn't exist in the database
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="App.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" placeholder="Enter your username">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <div class="register">
                <p>Don't have an account? <a href="register.php">Register Here</a>.</p>
            </div>
        </form>
    </div>
</body>
</html>