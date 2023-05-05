<?php
header('Content-Type: application/json');
require_once 'db_connect.php';
require_once 'jwt_helper.php';
$data = json_decode(file_get_contents('php://input'), true);
$token = $data['token'];
$decoded_token = JWT::decode($token, JWT_SECRET, array('HS256'));
$user_id = $decoded_token->user_id;
$age = filter_var($data['age'], FILTER_SANITIZE_NUMBER_INT);
$dob = filter_var($data['dob'], FILTER_SANITIZE_STRING);
$contact = filter_var($data['contact'], FILTER_SANITIZE_STRING);

// Validate input data
$errors = array();
if (!empty($age) && !filter_var($age, FILTER_VALIDATE_INT)) {
	$errors[] = 'Age must be a valid number';
}
if (!empty($dob) && !DateTime::createFromFormat('Y-m-d', $dob)) {
	$errors[] = 'Date of birth must be a valid date in the format YYYY-MM-DD';
}
if (!empty($contact) && strlen($contact) > 50) {
	$errors[] = 'Contact number cannot be longer than 50 characters';
}

if (empty($errors)) {
	// Sanitize input data
	$age = !empty($age) ? (int)$age : null;
	$dob = !empty($dob) ? new MongoDB\BSON\UTCDateTime(strtotime($dob) * 1000) : null;
	$contact = !empty($contact) ? $contact : null;

	// Update user profile in database
	$update_result = $db->users->updateOne(
		array('_id' => new MongoDB\BSON\ObjectID($user_id)),
		array('$set' => array('age' => $age, 'dob' => $dob, 'contact' => $contact))
	);

	if ($update_result->getModifiedCount() > 0) {
		echo json_encode(array('success' => true));
	} else {
		echo json_encode(array('success' => false, 'message' => 'Failed to update profile'));
	}
} else {
	echo json_encode(array('success' => false, 'errors' => $errors));
}
?>
