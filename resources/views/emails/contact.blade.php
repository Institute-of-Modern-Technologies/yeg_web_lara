<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Form Message</title>
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
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .message-box {
            background-color: #ffffff;
            border: 1px solid #eee;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>New Contact Form Message</h2>
    </div>
    
    <div class="content">
        <p>You have received a new message from the website contact form.</p>
        
        <div class="field">
            <div class="label">Name:</div>
            <div>{{ $contactData['name'] }}</div>
        </div>
        
        <div class="field">
            <div class="label">Email:</div>
            <div>{{ $contactData['email'] }}</div>
        </div>
        
        @if(isset($contactData['phone']) && !empty($contactData['phone']))
        <div class="field">
            <div class="label">Phone:</div>
            <div>{{ $contactData['phone'] }}</div>
        </div>
        @endif
        
        <div class="field">
            <div class="label">Subject:</div>
            <div>{{ $contactData['subject'] }}</div>
        </div>
        
        <div class="field">
            <div class="label">Message:</div>
            <div class="message-box">
                {{ $contactData['message'] }}
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>This email was sent from the Young Experts Group contact form.</p>
        <p>You can reply directly to this email to respond to the sender.</p>
    </div>
</body>
</html>
