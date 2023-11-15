<?php
session_start(); // Start a new or resume the existing session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Your database connection code
    $servername = "localhost"; // Your MySQL server hostname
    $db_username = "root";     // Your MySQL username
    $db_password = "";         // Your MySQL password
    $dbname = "db_ba3101";     // Your MySQL database name

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Define the student account table and columns
    $table_name = 'tb_studentacc';
    $username_column = 'Student_User';
    $password_column = 'Student_Password';

    // Authenticate the student against the student account table
    $sql = "SELECT Student_Acc_ID FROM $table_name WHERE $username_column='$username' AND $password_column='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Student authentication successful
        $row = $result->fetch_assoc();
        $student_acc_id = $row['Student_Acc_ID'];

        // Store user information in the session
        $_SESSION['student_acc_id'] = $student_acc_id;
        $_SESSION['username'] = $username;

        // Redirect to the student dashboard or another appropriate page
        header("Location: student_dashboard.php");
        exit;
    }

    // Define the admin account table and columns
    $table_name = 'tb_admin';
    $username_column = 'Admin_User';
    $password_column = 'Admin_Password';

    // Authenticate the admin against the admin account table
    $sql = "SELECT Admin_ID FROM $table_name WHERE $username_column='$username' AND $password_column='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Admin authentication successful
        $row = $result->fetch_assoc();
        $admin_id = $row['Admin_ID'];

        // Store user information in the session
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['username'] = $username;

        // Redirect to the admin dashboard or another appropriate page
        header("Location: admin_homedashboard.php");
        exit;
    }

    // Define the guidance account table and columns
    $table_name = 'tb_guidance';
    $username_column = 'Guidance_User';
    $password_column = 'Guidance_Password';

    // Authenticate the guidance against the guidance account table
    $sql = "SELECT Guidance_ID FROM $table_name WHERE $username_column='$username' AND $password_column='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Guidance authentication successful
        $row = $result->fetch_assoc();
        $guidance_id = $row['Guidance_ID'];

        // Store user information in the session
        $_SESSION['guidance_id'] = $guidance_id;
        $_SESSION['username'] = $username;

        // Redirect to the guidance dashboard or another appropriate page
        header("Location: guidance_homedashboard.php");
        exit;
    }

    // Handle cases of failed login
    header("Location: login.html?status=invalid_credentials");
    exit;

    $conn->close();
}
?>
