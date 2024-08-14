<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "epics";

// Admin email
$admin_email = "clubs5316@gmail.com"; // Change this to your admin email address

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: We're sorry, but there was an issue connecting to the database. Please try again later.");
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampnew\phpmailer\src\Exception.php';
require 'C:\xampnew\phpmailer\src\PHPMailer.php';
require 'C:\xampnew\phpmailer\src\SMTP.php';

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

    // Validate email, phone number, and roll number
    if (strlen($Phone) !== 10) {
        echo "<script>alert('Phone number should be exactly 10 digits.'); window.location='form.html';</script>";
        exit;
    }

    if (strlen($RollNo) !== 6) {
        echo "<script>alert('RollNo should be exactly 6 digits.'); window.location='form.html';</script>";
        exit;
    }

    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location='form.html';</script>";
        exit;
    }

    if (!preg_match("/^[0-9]{10}$/", $Phone)) {
        echo "<script>alert('Invalid phone number format.'); window.location='form.html';</script>";
        exit;
    }

    if (!preg_match("/^[12345678]2[0123][12](0[1-9]|[1-6][0-9]|7[0-5])@student.nitandhra.ac.in$/", $Email)) {
        echo "<script>alert('Invalid email format. Email should be in the form of rollnumber@student.nitap.ac.in'); window.location='form.html';</script>";
        exit;
    }

    if (!preg_match("/^[12345678]2[0123][12](0[1-9]|[1-6][0-9]|7[0-5])$/", $RollNo)) {
        echo "<script>alert('Invalid roll number format. Roll number should be of the format [12345678]2[0123][12](0[1-9]|[1-6][0-9]|7[0-5])'); window.location='form.html';</script>";
        exit;
    }
    
    // SQL query to insert form data into database
    $sql = "INSERT INTO formdata (Name, Email, Phone, RollNo, Branch, Clubs, Feedback) VALUES ('$Name', '$Email', '$Phone', '$RollNo', '$Branch', '$Clubs', '$Feedback')";

    try {
        if ($conn->query($sql) === TRUE) {
            // Data inserted successfully
            echo "New record created successfully";

            // Compose email message to user
            $to_user = $Email;
            $subject_user = "Club Registration Form Submission";
            $message_user = "Thank you for registering with us!<br><br>";
            $message_user .= "Name: $Name<br>";
            $message_user .= "Email: $Email<br>";
            $message_user .= "Phone: $Phone<br>";
            $message_user .= "Roll No: $RollNo<br>";
            $message_user .= "Branch: $Branch<br>";
            $message_user .= "Clubs: $Clubs<br>";
            $message_user .= "Feedback: $Feedback<br><br>";
            $message_user .= "We will get back to you shortly.";

            // Send email to user
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'clubs5316@gmail.com';
                $mail->Password = 'kegn qdiw rpms zgia';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                //Recipients
                $mail->setFrom('clubs5316@gmail.com', 'ClubAdmin');
                $mail->addAddress($to_user);     // Add a recipient

                //Content
                $mail->isHTML(true);                                 
                $mail->Subject = $subject_user;
                $mail->Body = $message_user;

                $mail->send();
                echo "Email sent successfully to user!";
            } catch (Exception $e) {
                echo "We're sorry, but there was an error sending the email. Please try again later.";
            }

            // Redirect after email and database operations
            header('Location: tnq.html');
            exit; 
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            // Duplicate entry error
            echo "<script>alert('We\\'re sorry, but it seems that you have already registered with this email address. Please use a different email address.'); window.location='form.html';</script>";
        } else {
            // Other error
            echo "<script>alert('We\\'re sorry, but there was an error processing your request. Please try again later.'); window.location='form.html';</script>";

        }
    }
}

// Close connection
$conn->close();
?>
