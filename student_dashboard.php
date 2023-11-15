<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Database connection parameters
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "db_ba3101";

// Create a connection to the database
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_acc_id = $_SESSION['student_acc_id'];

// Fetch student information including the image from the database based on the Student_Acc_ID from the session
$sql = "SELECT tb_studentinfo.SR_Code, tb_studentinfo.Student_Name, tb_studentinfo.Student_Course, tb_studentinfo.Student_Year, tb_studentinfo.Student_Picture
        FROM tb_studentinfo
        WHERE tb_studentinfo.Student_Acc_ID = $student_acc_id";

$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Fetch and store the student's information in PHP variables
    $row = $result->fetch_assoc();
    $srCode = $row['SR_Code'];
    $name = $row['Student_Name'];
    $course = $row['Student_Course'];
    $year = $row['Student_Year'];
    $imageData = $row['Student_Picture'];

    // Create a base64-encoded image string for display
    $imageData = base64_encode($imageData);
    $imageSrc = "data:image/jpeg;base64," . $imageData;
} else {
    // Handle the case where no data is found or multiple rows are returned
    // You can redirect the user or display an error message
    $srCode = "N/A";
    $name = "N/A";
    $course = "N/A";
    $year = "N/A";
    $imageSrc = "path_to_default_image.jpg"; // Replace with the path to a default image
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" type="text/css" href="student_dashboard.css">
</head>
<body>

    <div class="navbar">
        <div class="logo">
            <img src="BSUlogo.png" alt="Your Logo">
        </div>
        <ul class="menu">
            <li id="profile"><a href="student_dashboard.php">PROFILE</a></li>
            <li id="violation"><a href="student_violationlist.php">VIOLATION LIST</a></li>
            <li id="logout"><a href="login.html">LOGOUT</a></li>
        </ul>
    </div>

    <div class="student-info">
    <div class="student-info-details">
        <h2>Student Information</h2>
        <div class="student-image-box">
            <img src="<?php echo $imageSrc; ?>" alt="Student Image">
        </div>
        <p><strong>SR-Code:</strong> <?php echo $srCode; ?></p>
        <p><strong>Name:</strong> <?php echo $name; ?></p>
        <p><strong>Course:</strong> <?php echo $course; ?></p>
        <p><strong>Year:</strong> <?php echo $year; ?></p>
    </div>
    
</div>


</body>
</html>
