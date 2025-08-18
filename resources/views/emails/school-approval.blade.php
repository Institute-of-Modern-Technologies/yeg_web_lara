<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Registration Approved</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0F4C81;
            color: #ffffff;
            padding: 15px;
            border-radius: 5px 5px 0 0;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .credentials {
            background-color: #ffffff;
            border: 1px solid #eee;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .credentials-item {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            min-width: 120px;
        }
        .value {
            display: inline-block;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
            padding: 10px;
            border-top: 1px solid #eee;
        }
        .button {
            display: inline-block;
            background-color: #0F4C81;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: bold;
        }
        .note {
            background-color: #fff3cd;
            padding: 10px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>School Registration Approved!</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $school->name }},</p>
        
        <p>We are pleased to inform you that your school registration for the YEG Education Portal has been <strong>approved</strong>. Your school account has been created and is now active.</p>
        
        <div class="credentials">
            <h3>Your Login Credentials</h3>
            <div class="credentials-item">
                <span class="label">Username:</span>
                <span class="value">{{ $user->username }}</span>
            </div>
            <div class="credentials-item">
                <span class="label">Password:</span>
                <span class="value">{{ $password }}</span>
            </div>
            <div class="credentials-item">
                <span class="label">Portal URL:</span>
                <span class="value">{{ url('/school/login') }}</span>
            </div>
        </div>
        
        <div class="note">
            <p><strong>Important:</strong> For security reasons, we recommend changing your password after your first login.</p>
        </div>
        
        <p>With your school account, you can:</p>
        <ul>
            <li>Manage student registrations</li>
            <li>View and update school information</li>
            <li>Access educational resources</li>
            <li>Track student performance</li>
        </ul>
        
        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/school/login') }}" class="button">Login to School Portal</a>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} YEG Education. All rights reserved.</p>
        <p>This is an automated message. Please do not reply directly to this email.</p>
    </div>
</body>
</html>
