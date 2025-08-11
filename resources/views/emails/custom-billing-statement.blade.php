<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $customSubject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #7c3aed;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }
        .custom-message {
            white-space: pre-line;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .action-button {
            display: inline-block;
            background-color: #7c3aed;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #666;
            font-size: 14px;
        }
        .contact-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>Institute of Modern Technologies</h1>
            <p>{{ $customSubject }}</p>
        </div>

        <!-- Custom Message Content -->
        <div class="custom-message">
            {{ $customMessage }}
        </div>

        <!-- Action Button -->
        <div style="text-align: center;">
            <a href="{{ $billUrl }}" class="action-button">View Detailed Bill</a>
        </div>

        <!-- Contact Information -->
        <div class="contact-info">
            <h4>📞 Contact Information</h4>
            <p><strong>Institute of Modern Technologies</strong></p>
            <p>Email: info@imt.edu.gh</p>
            <p>Phone: +233 XX XXX XXXX</p>
            <p>Address: [Your Address Here]</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This email was sent from Institute of Modern Technologies.</p>
            <p>If you have any questions, please contact our office.</p>
            <p><small>Sent on {{ now()->format('F d, Y \a\t g:i A') }}</small></p>
        </div>
    </div>
</body>
</html>
