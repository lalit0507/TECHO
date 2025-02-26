<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = "";     // Default password for XAMPP (empty)
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS user_auth";
if ($conn->query($sql) === TRUE) {
    echo "Database 'user_auth' created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db("user_auth");

// Create the users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    registration_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validate inputs
    $errors = [];

    // Validate Username
    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Validate Phone Number (10 digits)
    if (!preg_match('/^\d{10}$/', $phone)) {
        $errors[] = "Please enter a valid 10-digit phone number.";
    }

    // Validate Password
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number.";
    } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        $errors[] = "Password must contain at least one special character.";
    }

    // Validate Confirm Password
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Check for duplicate entries
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR phone = ?");
    $check_stmt->bind_param("sss", $username, $email, $phone);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('User already exists. Please login.'); window.location.href = 'login.html';</script>";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the password
        $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $phone, $hashedPassword);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href = 'login.html';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Display errors
        echo "<script>alert('" . implode("\\n", $errors) . "'); window.location.href = 'index.html';</script>";
    }

    $check_stmt->close();
}

// Close the connection
$conn->close();
?>
