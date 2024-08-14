<?php
include("config.php");
session_start();

$errmsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email=$_POST["email"];
    $first_name=$_POST["first_name"];
    $last_name=$_POST["last_name"];
    $phone=$_POST["phone"];
    $user_id=$_POST["user_id"];
    $password=$_POST["password"];

    // Check if any of the required fields are empty
    if (empty($email) || empty($first_name) || empty($last_name) || empty($phone) || empty($user_id) || empty($password)) {
        $errmsg = "All fields are required";
    } elseif (strlen($phone) != 10) {
        $errmsg = "Phone number should be exactly 10 digits";
    } elseif (!preg_match("/^[12345678]2[0123][12](0[1-9]|[1-6][0-9]|7[0-5])@student.nitandhra.ac.in$/", $email)) {
        $errmsg = "Invalid email format. Email should be in the form of rollnumber@student.nitap.ac.in";
    } elseif (strlen($password) < 8 || !preg_match("/^(?=.*[!@#$%^&*(),.?\":{}|<>])(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
        $errmsg = "Password should be at least 8 characters long and contain at least one special character, one capital letter, and one number";
    } else {
        $sql = "INSERT INTO signup (email, first_name, last_name, phone, user_id, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $email, $first_name, $last_name, $phone, $user_id, $password);
            try {
                mysqli_stmt_execute($stmt);
                // Entry successful, redirect to login page
                header("Location: login.html");
                exit();
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {
                    $errmsg = "The user ID or email is already registered. Please use a different one.";
                    header("Location: signup.html");
                } else {
                    $errmsg = "An error occurred while processing your request. Please try again later.";
                    header("Location: signup.html");
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $errmsg = "An error occurred while processing your request. Please try again later.";
            header("Location: signup.html");

        }
    }
} 

// If there's an error, display it as an alert and stay on the same page
if ($errmsg !== '') {
    echo "<script>alert(\"$errmsg\"); window.location.href = 'signup.html';</script>";
    exit();
}
?>
