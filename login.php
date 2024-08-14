<?php
require_once 'config.php';

// Assuming the form method is POST
$user_id = $_POST["user_id"];
$password = $_POST["password"];

// Prepare the SQL statement with placeholders for user ID and password
$query = "SELECT * FROM signup WHERE user_id = ? AND password = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "ss", $user_id, $password);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the user data
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($user) {
        // User exists and password matches
        header("Location: portal.html");
        exit();
    } else {
        // Display error message as an alert using JavaScript
        echo "<script>alert('User ID or Password is incorrect');</script>";
        echo "<script>window.location.href = 'login.html';</script>";
        exit();
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // Handle error if statement preparation fails
    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    echo "<script>window.location.href = 'login.html';</script>";
    exit();
}

// Close the connection
mysqli_close($conn);
?>
