<?php
session_start();
header('Content-Type: application/json');

// Define the expected verification code
$expectedCode = '123456'; // Change this to your desired verification code

// Get the code from the request
$inputCode = json_decode(file_get_contents('php://input'), true)['verificationCode'] ?? '';

// Check if the code matches
if ($inputCode === $expectedCode) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid verification code.']);
}
?>