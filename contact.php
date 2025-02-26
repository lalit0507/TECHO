<?php
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

// Create the contact_responses table if it doesn't exist
$createTableQuery = "
CREATE TABLE IF NOT EXISTS contact_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($createTableQuery) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Validate input (basic validation)
    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('Please fill in all fields.'); window.location.href = 'home.html';</script>";
        exit();
    }

    // Insert data into the database
    $sql = "INSERT INTO contact_responses (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $message);
    if ($stmt->execute()) {
        echo "<script>alert('Thank you for contacting us! We will get back to you soon.'); window.location.href = 'home.html';</script>";
    } else {
        echo "<script>alert('Error submitting your message. Please try again.'); window.location.href = 'home.html';</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>