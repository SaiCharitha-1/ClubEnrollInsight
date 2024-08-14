<!DOCTYPE html>
<html>
<head>
    <title>Club Details</title>
    <style>
        /* CSS for centering club name */
        h1 {
            text-align: center;
            margin-top: 50px;
            font-size: 66px;
            color: #000; /* Change text color to black */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Add text shadow for better visibility */
        }

        .container {
            display: flex;
            justify-content: center;
        }

        .table-container {
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
            transition: all 0.3s ease;
        }

        th {
            background-color: #4c75af;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f0f0f0;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Container for background image */
        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('images/nit-logo.png'); /* Add background image */
            background-size: contain; /* Fit the image within the container */
            background-repeat: no-repeat;
            background-position: center;
            filter: blur(8px); /* Apply blur effect to the background image */
            z-index: -1; /* Send the background behind other content */
        }

        /* Button styles */
        /* CSS for centering the back button at the bottom of the page */
        .back-button {
            position: fixed;
            bottom: 90px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }

        .back-button a {
            background-color: #4c75af;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-button a:hover {
            background-color: #375d82;
        }

    </style>
</head>
<body>

<div class="background-container"></div> <!-- Container for background image -->

<?php
// Establish a connection to the database (assuming you have a connection file)
include 'config.php';

// Check if club_name parameter is set in the URL
if(isset($_GET['club_name'])) {
    // Get the club name from the URL parameter
    $club_name = $_GET['club_name'];

    // Fetch club details including coordinator and members information
    $query = "SELECT c.club_name, fc.name AS coordinator_name, fc.email AS coordinator_email, fc.department AS coordinator_department, m.first_name AS member_first_name, m.last_name AS member_last_name, m.email AS member_email, m.phone AS member_phone, m.user_id AS member_user_id
    FROM clubs c
    LEFT JOIN faculty_coordinator fc ON c.club_id = fc.club_id
    LEFT JOIN club_members cm ON c.club_id = cm.club_id
    LEFT JOIN signup m ON cm.user_id = m.user_id
    WHERE c.club_name = '$club_name'";

    // Execute query
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) > 0) {
        // Display club details
        echo "<h1>$club_name</h1>";

        echo "<div class='container'>";

        // Coordinator Table
        echo "<div class='table-container'>";
        echo "<h2>Coordinator</h2>";
        echo "<table>";
        echo "<tr><th>Name</th><th>Email</th><th>Department</th></tr>";

        // Fetch coordinator data
        $coordinator = mysqli_fetch_assoc($result);

        // Display coordinator details
        echo "<tr>";
        echo "<td>" . $coordinator['coordinator_name'] . "</td>";
        echo "<td>" . $coordinator['coordinator_email'] . "</td>";
        echo "<td>" . $coordinator['coordinator_department'] . "</td>";
        echo "</tr>";

        echo "</table>";
        echo "</div>";

        // Reset result pointer to fetch members' data
        mysqli_data_seek($result, 0);

        // Members Table
        echo "<div class='table-container'>";
        echo "<h2>Club Members</h2>";
        echo "<table>";
        echo "<tr><th>Name</th><th>Email</th><th>Phone</th><th>User ID</th></tr>";

        // Loop through the rows for members
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['member_first_name'] . " " . $row['member_last_name'] . "</td>";
            echo "<td>" . $row['member_email'] . "</td>";
            echo "<td>" . $row['member_phone'] . "</td>";
            echo "<td>" . $row['member_user_id'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "</div>";

    } else {
        echo "No data found.";
    }
} else {
    echo "Club name parameter is missing.";
}
?>

<div class="back-button">
    <a href="clubs.html">Back to Clubs Page</a>
</div>

</body
