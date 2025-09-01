<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Get OTP
$inputOtp = $_POST['otp'] ?? '';
$phone = $_POST['phone'] ?? '';

// Validate input
if (empty($inputOtp) || !preg_match('/^[0-9]{6}$/', $inputOtp)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid OTP format']);
    exit;
}

// Check if OTP exists in session
if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiry']) || !isset($_SESSION['otp_phone'])) {
    echo json_encode(['status' => 'error', 'message' => 'No OTP found. Please request new OTP']);
    exit;
}

// Check if OTP expired
if (time() > $_SESSION['otp_expiry']) {
    // Clear expired OTP
    unset($_SESSION['otp'], $_SESSION['otp_phone'], $_SESSION['otp_time'], $_SESSION['otp_expiry']);
    echo json_encode(['status' => 'error', 'message' => 'OTP expired. Please request new OTP']);
    exit;
}

// Check if phone matches
if ($phone !== $_SESSION['otp_phone']) {
    echo json_encode(['status' => 'error', 'message' => 'Phone number mismatch']);
    exit;
}

// Check if OTP matches
if ($inputOtp != $_SESSION['otp']) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
    exit;
}

// OTP verified successfully
$_SESSION['authenticated'] = true;
$_SESSION['user_phone'] = $phone;

// Clear OTP data
unset($_SESSION['otp'], $_SESSION['otp_phone'], $_SESSION['otp_time'], $_SESSION['otp_expiry']);

echo json_encode([
    'status' => 'success', 
    'message' => 'OTP verified successfully',
    'redirect' => '/dashboard.php'
]);
?>
