<?php
// Include database connection details
require_once 'db.php';

// Validate and sanitize form inputs
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$response = array('status' => 'error', 'message' => 'Invalid email format');
	echo json_encode($response);
	exit;
}

// Prepare and execute the database insertion query using prepared statements
$stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $name, $email, password_hash($password, PASSWORD_DEFAULT));
if ($stmt->execute()) {
	$response = array('status' => 'success');
} else {
	$response = array('status' => 'error', 'message' => 'Database insertion error');
}

// Return response to AJAX call
echo json_encode($response);
