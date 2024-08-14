<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "epics";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $Name = $_POST['name'];
    $Email = $_POST['email'];
    $Phone = $_POST['phone'];
    $RollNo = $_POST['roll'];
    $Branch = $_POST['branch'];
    $Clubs = implode(', ', $_POST['clubs']);
    $Feedback = $_POST['feedback'];

    // Validate form inputs
    $errmsg = "";
    if (empty($Name) || empty($Email) || empty($Phone) || empty($RollNo) || empty($Branch) || empty($Clubs)) {
        $errmsg = "*All fields are required";
    } elseif (strlen($Phone) != 10) {
        $errmsg = "*Phone number should be exactly 10 digits";
    } elseif (strlen($RollNo) != 6) {
        $errmsg = "*Roll No. should be exactly 6 digits";
    }
    elseif (!preg_match("/^\d+@student\.nitandhra\.ac\.in$/", $Email)) {
        $errmsg = "*Invalid email format. Email should be in the form of rollnumber@student.nitap.ac.in";
    } else {
        // Insert user details into database
        $sql = "INSERT INTO formdata (Name, Email, Phone, RollNo, Branch, Clubs, Feedback) VALUES (?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssss", $Name, $Email, $Phone, $RollNo, $Branch, $Clubs, $Feedback);
            $query = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($query) {
                // Success message
                echo "Form submitted successfully!";
            } else {
                $errmsg = "*Error submitting form data";
            }
        } else {
            $errmsg = "*SQL preparation failed";
        }
    }

    if (!empty($errmsg)) {
        echo $errmsg;
    }
}

// Segregate entries by clubs and display
