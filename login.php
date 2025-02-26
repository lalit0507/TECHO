<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = "";     // Default password for XAMPP (empty)
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle user login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emailPhone = trim($_POST['username']); // Can be email or phone
    $password = trim($_POST['password']); // Trim whitespace

    // Validate input
    if (empty($emailPhone) || empty($password)) {
        echo "<script>alert('Please fill in all fields.'); window.location.href = 'login.html';</script>";
        exit();
    }

    // Check if input is email or phone
    $isEmail = filter_var($emailPhone, FILTER_VALIDATE_EMAIL);
    $isPhone = preg_match('/^\d{10}$/', $emailPhone);

    if (!$isEmail && !$isPhone) {
        echo "<script>alert('Please enter a valid email or 10-digit phone number.'); window.location.href = 'login.html';</script>";
        exit();
    }

    // Fetch user from the database using prepared statements
    $sql = "SELECT * FROM users WHERE email = ? OR phone = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $emailPhone, $emailPhone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['username'] = $user['username'];
            header("Location: home.html"); // Redirect to home.html
            exit();
        } else {
            // Invalid password
            echo "<script>alert('Wrong password.'); window.location.href = 'login.html';</script>";
            exit();
        }
    } else {
        // User not found
        echo "<script>alert('User not found.'); window.location.href = 'login.html';</script>";
        exit();
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>