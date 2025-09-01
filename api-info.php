<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$api_info = [
    'service' => 'QuickZen OTP API',
    'version' => '1.0',
    'endpoints' => [
        'send_otp' => [
            'url' => '/send-otp.php',
            'method' => 'POST',
            'params' => [
                'phone' => '10-digit mobile number'
            ],
            'response' => [
                'success' => ['status' => 'success', 'message' => 'OTP sent successfully'],
                'error' => ['status' => 'error', 'message' => 'Error description']
            ]
        ],
        'verify_otp' => [
            'url' => '/verify-otp.php',
            'method' => 'POST', 
            'params' => [
                'phone' => '10-digit mobile number',
                'otp' => '6-digit OTP'
            ],
            'response' => [
                'success' => ['status' => 'success', 'message' => 'OTP verified successfully', 'redirect' => '/dashboard.php'],
                'error' => ['status' => 'error', 'message' => 'Error description']
            ]
        ]
    ],
    'otp_format' => '6-digit numeric (100000-999999)',
    'otp_expiry' => '5 minutes',
    'phone_format' => '10-digit Indian mobile number',
    'example_usage' => [
        'send_otp' => "fetch('YOUR_BACKEND_URL/send-otp.php', { method: 'POST', body: 'phone=7864049676' })",
        'verify_otp' => "fetch('YOUR_BACKEND_URL/verify-otp.php', { method: 'POST', body: 'phone=7864049676&otp=123456' })"
    ]
];

echo json_encode($api_info, JSON_PRETTY_PRINT);
?>
