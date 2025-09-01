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

// Get phone number
$phone = $_POST['phone'] ?? '';

// Validate phone
if (empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid phone number']);
    exit;
}

// Generate 6-digit OTP
$otp = rand(100000, 999999);

// Store in session with expiry (5 minutes)
$_SESSION['otp'] = $otp;
$_SESSION['otp_phone'] = $phone;
$_SESSION['otp_time'] = time();
$_SESSION['otp_expiry'] = time() + (5 * 60); // 5 minutes

// Send to WhatsApp via Node.js
$whatsappUrl = 'http://localhost:3000/send-whatsapp'; // Same server
$postData = json_encode([
    'phone' => $phone,
    'otp' => $otp
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => $postData
    ]
]);

$whatsappResponse = file_get_contents($whatsappUrl, false, $context);

if ($whatsappResponse === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP']);
    exit;
}

echo json_encode([
    'status' => 'success', 
    'message' => 'OTP sent successfully'
]);
?>
