<?php
session_start();

// Define your database connection parameters
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "db_ba3101";

// Create a connection to the database
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
$username = $_SESSION['username'];

// Search functionality
if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $sql = "SELECT * FROM tb_violation WHERE
            Violation_ID LIKE '%$searchTerm%' OR
            Student_Name LIKE '%$searchTerm%' OR
            Student_Course LIKE '%$searchTerm%' OR
            Student_Year LIKE '%$searchTerm%' OR
            Violation_Date LIKE '%$searchTerm%' OR
            Violation_Description LIKE '%$searchTerm%' OR
            Violation_Status LIKE '%$searchTerm%' OR
            SR_Code LIKE '%$searchTerm%' OR
            Violation_Offense LIKE '%$search%' OR
            Violation_Penalties LIKE '%search%'";
} else {
    // Retrieve all records by default
    $sql = "SELECT * FROM tb_violation";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="admin_dashboard.css">
</head>
<body>

    <div class="navbar">
        <div class="logo">
            <img src="BSUlogo.png" alt="Your Logo">
        </div>
        <ul class="menu">
            <li id="home"><a href="admin_homedashboard.php">HOME</a></li>
            <li id="violation"><a href="admin_dashboard.php">VIOLATION LIST</a></li>
            <li id="print-button"><a href="#">PRINT REPORT</a></li>
            <li id="logout"><a href="login.html">LOGOUT</a></li>
                     <!-- Add a link to the registration page -->
            <li id="createacc"><a href="registrationadmin.php">Create Account</a></li>

        </ul>
    </div>

    <div class="admin-dashboard">
        <h2>Admin Dashboard</h2>
        <form method="post" action="">
            <input type="text" name="searchTerm" placeholder="Search...">
            <input type="submit" name="search" value="Search">
        </form>

        <!-- Surround the table with a div -->
        <div id="print-content">
            <table>
                <tr>
                    <th>Violation ID</th>
                    <th>Student Name</th>
                    <th>Student Course</th>
                    <th>Student Year</th>
                    <th>Violation Date</th>
                    <th>Violation Description</th>
                    <th>Violation Status</th>
                    <th>SR Code</th>
                    <th>Violation Offense</th>
                    <th>Violation Penalties</th>
                </tr>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['Violation_ID'] . "</td>";
                    echo "<td>" . $row['Student_Name'] . "</td>";
                    echo "<td>" . $row['Student_Course'] . "</td>";
                    echo "<td>" . $row['Student_Year'] . "</td>";
                    echo "<td>" . $row['Violation_Date'] . "</td>";
                    echo "<td>" . $row['Violation_Description'] . "</td>";
                    echo "<td>" . $row['Violation_Status'] . "</td>";
                    echo "<td>" . $row['SR_Code'] . "</td>";
                    echo "<td>" . $row['Violation_Offense'] . "</td>";
                    echo "<td>" . $row['Violation_Penalties'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('print-button').addEventListener('click', function() {
            // Clone the table content to a new window for printing
            var printWindow = window.open('', '', 'width=600,height=600');
            var content = document.getElementById('print-content').innerHTML;
            printWindow.document.open();
            printWindow.document.write('<html><head><title>Print Report</title></head><body>' + content + '</body></html>');
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        });
    </script>

</body>
</html>
