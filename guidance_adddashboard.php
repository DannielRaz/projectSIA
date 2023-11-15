<?php
session_start();

// Your database connection code
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "db_ba3101";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
$username = $_SESSION['username'];

// Initialize variables
$studentName = $studentCourse = $studentYear = $violationDescription = $violationStatus = $srCode = $violationOffense = $violationPenalties ="";
$editMode = false;

// Handle adding/editing violation records
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        // Handle adding a new violation record
        $studentName = $_POST['studentName'];
        $studentCourse = $_POST['studentCourse'];
        $studentYear = $_POST['studentYear'];
        $violationDescription = $_POST['violationDescription'];
        $violationStatus = $_POST['violationStatus'];
        $srCode = $_POST['srCode']; // Assuming this is selected from a dropdown
        $violationOffense = $_POST['violationOffense'];
        $violationPenalties = $_POST['violationPenalties'];
        // Set both Admin_ID and Guidance_ID to 1
        $adminId = 1;
        $guidanceId = 1;

        $violationDate = date('Y-m-d H:i:s'); // Get the current date and time

        // Check if the SR_Code exists in the tb_studentinfo table
        $checkSRCodeQuery = "SELECT COUNT(*) AS count FROM tb_studentinfo WHERE SR_Code = '$srCode'";
        $result = $conn->query($checkSRCodeQuery);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $count = $row['count'];

            if ($count > 0) {
                // The SR_Code exists in tb_studentinfo, so you can insert the violation record
                $insertViolationQuery = "INSERT INTO tb_violation (Violation_Date, Student_Name, Student_Course, Student_Year, Violation_Description, Violation_Status, SR_Code, Admin_ID, Guidance_ID, Violation_Offense, Violation_Penalties)
                    VALUES ('$violationDate', '$studentName', '$studentCourse', '$studentYear', '$violationDescription', '$violationStatus', '$srCode', '$adminId', '$guidanceId', '$violationOffense', '$violationPenalties')";

                if ($conn->query($insertViolationQuery) === TRUE) {
                    
                } else {
                    echo "Error: " . $insertViolationQuery . "<br>" . $conn->error;
                }
            } else {
                echo "Error: The SR_Code does not exist in the tb_studentinfo table.";
            }
        } else {
            echo "Error: Unable to verify the SR_Code.";
        }
    } elseif (isset($_POST['edit'])) {
        // Handle editing an existing violation record
        $editMode = true;
        $violationID = $_POST['violationID'];

        // Fetch the existing violation record for editing
        $getViolationQuery = "SELECT * FROM tb_violation WHERE Violation_ID = '$violationID'";
        $result = $conn->query($getViolationQuery);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $studentName = $row['Student_Name'];
            $studentCourse = $row['Student_Course'];
            $studentYear = $row['Student_Year'];
            $violationDescription = $row['Violation_Description'];
            $violationStatus = $row['Violation_Status'];
            $srCode = $row['SR_Code'];
            $violationOffense = $row['Violation_Offense'];
            $violationPenalties = $row['Violation_Penalties'];
        } else {
            echo "Error: Violation record not found.";
        }
    } elseif (isset($_POST['update'])) {
        // Handle updating an existing violation record
        $violationID = $_POST['violationID'];
        $studentName = $_POST['studentName'];
        $studentCourse = $_POST['studentCourse'];
        $studentYear = $_POST['studentYear'];
        $violationDescription = $_POST['violationDescription'];
        $violationStatus = $_POST['violationStatus'];
        $srCode = $_POST['srCode']; 
        $violationOffense = $_POST['violationOffense'];
        $violationPenalties = $_POST['violationPenalties'];
        // Assuming this is selected from a dropdown

        // Check if the SR_Code exists in the tb_studentinfo table
        $checkSRCodeQuery = "SELECT COUNT(*) AS count FROM tb_studentinfo WHERE SR_Code = '$srCode'";
        $result = $conn->query($checkSRCodeQuery);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $count = $row['count'];

            if ($count > 0) {
                // The SR_Code exists in tb_studentinfo, so you can update the violation record
                $updateViolationQuery = "UPDATE tb_violation SET Student_Name = '$studentName', Student_Course = '$studentCourse', 
                    Student_Year = '$studentYear', Violation_Description = '$violationDescription', Violation_Status = '$violationStatus', 
                    SR_Code = '$srCode', Violation_Offense = '$violationOffense', Violation_Penalties = '$violationPenalties' WHERE Violation_ID = '$violationID'";

                if ($conn->query($updateViolationQuery) === TRUE) {
                    
                    $editMode = false;
                } else {
                    echo "Error: " . $updateViolationQuery . "<br>" . $conn->error;
                }
            } else {
                echo "Error: The SR_Code does not exist in the tb_studentinfo table.";
            }
        } else {
            echo "Error: Unable to verify the SR_Code.";
        }
    } elseif (isset($_POST['delete'])) {
        // Handle deleting an existing violation record
        $violationID = $_POST['violationID'];

        // Delete the violation record
        $deleteViolationQuery = "DELETE FROM tb_violation WHERE Violation_ID = '$violationID'";

        if ($conn->query($deleteViolationQuery) === TRUE) {
            
        } else {
            echo "Error: " . $deleteViolationQuery . "<br>" . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Dashboard</title>
    <link rel="stylesheet" type="text/css" href="guidance_dashboard.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="BSUlogo.png" alt="Your Logo">
        </div>
        <ul class="menu">
            <li id="home"><a href="guidance_homedashboard.php">HOME</a></li>
            <li id="add"><a href="guidance_adddashboard.php">ADD VIOLATION</a></li>
            <li id="list"><a href="guidance_dashboard.php">LIST</a></li>
            <li id="logout"><a href="login.html">LOGOUT</a></li>
            <li id="createacc"><a href="registrationguidance.php">Create Account</a></li>

        </ul>
    </div>
    <h2>Security Guard with Shotgun (double barrel)Add Violation</h2>

    <!-- Form for adding/editing violation records -->
<div class="form-container">
    <form method="post" action="guidance_adddashboard.php">
        <input type="hidden" name="violationID" value="<?php echo $editMode ? $violationID : '' ?>">
        <input type="text" id="studentName" name="studentName" placeholder="Student Name" value="<?php echo $studentName ?>" required>
        <input type="text" id="studentCourse" name="studentCourse" placeholder="Student Course" value="<?php echo $studentCourse ?>" required>
        <input type="text" id="studentYear" name="studentYear" placeholder="Student Year" value="<?php echo $studentYear ?>" required>
        <input type="text" id="violationDescription" name="violationDescription" placeholder="Violation Description" value="<?php echo $violationDescription ?>" required>
        <input type="text" id="violationStatus" name="violationStatus" placeholder="Violation Status" value="<?php echo $violationStatus ?>" required>
        <input type="text" id="srCode" name="srCode" placeholder="SR Code" value="<?php echo $srCode ?>" required>
        <input type="text" id="violationOffense" name="violationOffense" placeholder="Violation Offense" value="<?php echo $violationOffense ?>" required>
        <input type="text" id="violationPenalties" name="violationPenalties" placeholder="Violation Penalties" value="<?php echo $violationPenalties ?>" required>
        
        <?php if ($editMode) { ?>
            <input type="submit" name="update" value="Update Violation">
            <button type="button" id="clearButton">Clear</button> <!-- Add "Clear" button for edit mode -->
        <?php } else { ?>
            <input type="submit" name="add" value="Add Violation">
            <button type="button" id="clearButton">Clear</button> <!-- Add "Clear" button for edit mode -->
        <?php } ?>
    </form>
</div>

<script>
    document.getElementById('clearButton').addEventListener('click', function() {
        // Clear the form fields
        document.getElementById('studentName').value = '';
        document.getElementById('studentCourse').value = '';
        document.getElementById('studentYear').value = '';
        document.getElementById('violationDescription').value = '';
        document.getElementById('violationStatus').value = '';
        document.getElementById('srCode').value = '';
        document.getElementById('violationOffense').value = '';
        document.getElementById('violationPenalties').value = '';
    });
</script>

</body>
</html>

