<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "ronythite@007";
$dbname = "dhanashri";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get the JSON input
$data = json_decode(file_get_contents("php://input"));

// Validate JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["message" => "Invalid JSON data."]);
    exit;
}

// Extract data from JSON
$name = $data->name ?? '';
$contact = $data->contact ?? '';
$comment = $data->comment ?? '';
$rating = $data->rating ?? 0; // Default to 0 if not set

// Prepare SQL statement
$sql = "INSERT INTO feedback (name, contact, comment, rating) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["message" => "SQL prepare error: " . $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param("sssi", $name, $contact, $comment, $rating);

// Execute statement and check for errors
if ($stmt->execute()) {
    echo json_encode(["message" => "Thank you for your feedback!"]);
} else {
    echo json_encode(["message" => "Error submitting feedback: " . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
