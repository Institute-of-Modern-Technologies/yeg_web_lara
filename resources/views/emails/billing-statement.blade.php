<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Statement - Institute of Modern Technologies</title>
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
        .student-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .student-info h3 {
            color: #7c3aed;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .billing-summary {
            margin-bottom: 25px;
        }
        .billing-summary h3 {
            color: #7c3aed;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .summary-item:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
            color: #7c3aed;
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .balance-warning {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .balance-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
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
            <p>Billing Statement</p>
        </div>

        <!-- Greeting -->
        <p>Dear {{ $student->first_name }} {{ $student->last_name }},</p>
        <p>We hope this email finds you well. Please find your current billing statement below:</p>

        <!-- Student Information -->
        <div class="student-info">
            <h3>📋 Student Information</h3>
            <div class="info-row">
                <span class="info-label">Full Name:</span>
                <span>{{ $student->first_name }} {{ $student->last_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Student ID:</span>
                <span>{{ $student->student_id ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Program:</span>
                <span>{{ $student->programType->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">School:</span>
                <span>{{ $student->school->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span>{{ $student->email }}</span>
            </div>
        </div>

        <!-- Billing Summary -->
        <div class="billing-summary">
            <h3>💰 Payment Summary</h3>
            <div class="summary-item">
                <span>Amount to be Paid:</span>
                <span>GH₵{{ number_format($amountToBePaid, 2) }}</span>
            </div>
            <div class="summary-item">
                <span>Amount Paid:</span>
                <span>GH₵{{ number_format($totalPaid, 2) }}</span>
            </div>
            <div class="summary-item">
                <span>Outstanding Balance:</span>
                <span>GH₵{{ number_format($balance, 2) }}</span>
            </div>
        </div>

        <!-- Balance Status -->
        @if($balance > 0)
            <div class="balance-warning">
                <strong>⚠️ Outstanding Balance</strong><br>
                You have an outstanding balance of <strong>GH₵{{ number_format($balance, 2) }}</strong>. 
                Please contact our office to arrange payment or make a payment as soon as possible.
            </div>
        @else
            <div class="balance-success">
                <strong>✅ Account Fully Paid</strong><br>
                Congratulations! Your account is fully paid. Thank you for your prompt payment.
            </div>
        @endif

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
            <p>This is an automated email from Institute of Modern Technologies.</p>
            <p>If you have any questions about your billing statement, please contact our office.</p>
            <p><small>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</small></p>
        </div>
    </div>
</body>
</html>
