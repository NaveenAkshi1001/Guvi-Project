<?php
// connect to the database
$conn = new mysqli('localhost', 'username', 'password', 'database_name');
if ($conn->connect_error) {
	die('Connection failed: ' . $conn->connect_error);
}

// validate form inputs
if (empty($_POST['email']) || empty($_POST['password'])) {
	echo json_encode(array('status' => 'error', 'message' => 'Please enter both email and password.'));
	exit();
}

// sanitize form data
$email = $conn->real_escape_string($_POST['email']);
$password = $conn->real_escape_string($_POST['password']);

// verify user credentials against the database
$stmt = $conn->prepare('SELECT id FROM users WHERE email = ? AND password = ?');
$stmt->bind_param('ss', $email, $password);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 1) {
	// on successful login, create a unique token and store it in the browser's local storage
	$token = bin2hex(random_bytes(32));
	echo json_encode(array('status' => 'success', 'token' => $token));
} else {
	echo json_encode(array('status' => 'error', 'message' => 'Incorrect email or password.'));
}
$stmt->close();
$conn->close();
?>
