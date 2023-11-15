<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Your database connection code
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "db_ba3101";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Set the role to 'admin'
    $role = 'admin';

    // Determine the table and columns based on the selected role
    if ($role === 'admin') {
        $table_name = 'tb_admin';
        $username_column = 'Admin_User';
        $password_column = 'Admin_Password';
    } else {
        // Handle invalid role
        header("Location: registrationadmin.php?status=invalid_role");
        exit;
    }

    // Check if the username already exists
    $check_username_sql = "SELECT * FROM $table_name WHERE $username_column='$username'";
    $check_username_result = $conn->query($check_username_sql);

    if ($check_username_result->num_rows > 0) {
        // Username already exists, handle accordingly (e.g., redirect with a message)
        header("Location: registrationadmin.php?status=username_exists");
        exit;
    }

    // Insert the new user into the database
    $insert_sql = "INSERT INTO $table_name ($username_column, $password_column) VALUES ('$username', '$password')";
    if ($conn->query($insert_sql) === TRUE) {
        // Registration successful, redirect to login page
        header("Location: login.html");
        exit;
    } else {
        // Registration failed, handle accordingly
        header("Location: registrationadmin.php?status=registration_failed");
        exit;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link rel="stylesheet" type="text/css" href="registration.css">
</head>
<body>
    <div class="registration-container">
        <h2>Create Account Admin</h2>
        <form action="registrationadmin.php" method="post">
        
            <label for="username"></label>
            <input type="text" id="username" name="username" placeholder="Username" required>

            <label for="password"></label>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <!-- Role set to 'admin' automatically -->
            <input type="hidden" id="role" name="role" value="admin">

            <input type="submit" value="Create Account">
        </form>
        <button onclick="goToLoginPage()">Admin Dashboard</button>
    </div>

    <script>
        // Get the status from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        // Display a pop-up message if the status is present
        if (status) {
            let message = '';

            switch (status) {
                case 'invalid_role':
                    message = 'Invalid role selected.';
                    break;
                case 'username_exists':
                    message = 'Username already exists. Please choose a different username.';
                    break;
                case 'registration_failed':
                    message = 'Registration failed. Please try again.';
                    break;
                default:
                    // Handle other status values as needed
                    break;
            }

            if (message !== '') {
                alert(message);
            }
        }

        function goToLoginPage() {
            window.location.href = 'admin_homedashboard.php';
        }
    </script>
</body>
</html>
