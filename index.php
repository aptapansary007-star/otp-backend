<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickZen OTP Backend</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .logo {
            background: linear-gradient(90deg, #003ba2, #005adf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .status {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            margin: 1rem 0;
            font-weight: bold;
        }
        .status.active { background: #d4edda; color: #155724; }
        .status.inactive { background: #f8d7da; color: #721c24; }
        .api-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1.5rem 0;
            text-align: left;
        }
        .endpoint {
            background: #e3f2fd;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border-radius: 5px;
            border-left: 4px solid #2196f3;
        }
        code { 
            background: #f5f5f5; 
            padding: 0.2rem 0.4rem; 
            border-radius: 3px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="logo">QuickZen</h1>
        <h2>OTP Backend Service</h2>
        
        <?php
        // Check if WhatsApp Node.js server is running
        $whatsapp_status = @file_get_contents('http://localhost:3000/', false, stream_context_create([
            'http' => ['timeout' => 2]
        ]));
        
        $is_active = $whatsapp_status !== false;
        ?>
        
        <div class="status <?= $is_active ? 'active' : 'inactive' ?>">
            Backend Status: <?= $is_active ? '✅ Active' : '❌ Inactive' ?>
        </div>
        
        <?php if ($is_active): ?>
            <div class="status active">WhatsApp Service: ✅ Running</div>
        <?php else: ?>
            <div class="status inactive">WhatsApp Service: ❌ Not Running</div>
            <p style="color: #dc3545; margin-top: 1rem;">
                Run: <code>npm start</code> to start WhatsApp service
            </p>
        <?php endif; ?>
        
        <div class="api-info">
            <h3>🔌 API Endpoints</h3>
            
            <div class="endpoint">
                <strong>Send OTP</strong><br>
                <code>POST /send-otp.php</code><br>
                Params: <code>phone=8250560727</code>
            </div>
            
            <div class="endpoint">
                <strong>Verify OTP</strong><br>
                <code>POST /verify-otp.php</code><br>
                Params: <code>phone=8250560727&otp=123456</code>
            </div>
            
            <div class="endpoint">
                <strong>API Info</strong><br>
                <code>GET /api-info.php</code><br>
                Complete API documentation
            </div>
        </div>
        
        <p style="color: #666; margin-top: 1rem;">
            Backend deployed on Render.com<br>
            Frontend should be hosted separately
        </p>
    </div>
</body>
</html>
