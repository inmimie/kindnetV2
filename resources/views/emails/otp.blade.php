<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: 'Figtree', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 40px;
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 800;
            color: #4f46e5;
            margin-bottom: 24px;
            letter-spacing: -0.025em;
        }
        .title {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .description {
            font-size: 15px;
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .otp-container {
            background-color: #f5f3ff;
            border: 2px dashed #c084fc;
            border-radius: 8px;
            padding: 16px 24px;
            display: inline-block;
            margin-bottom: 30px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 800;
            color: #7c3aed;
            letter-spacing: 0.25em;
            margin: 0;
            padding-left: 0.25em; /* offsets the right spacing of the last character */
        }
        .expiry {
            font-size: 13px;
            color: #9ca3af;
            margin-top: 0;
            margin-bottom: 24px;
        }
        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 30px 0;
        }
        .footer {
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.5;
        }
        .footer a {
            color: #4f46e5;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">KindNet</div>
            <h1 class="title">Verify Your Identity</h1>
            <p class="description">
                We received a request to reset your password. Use the verification code below to proceed with setting up a new password. If you did not make this request, you can safely ignore this email.
            </p>
            
            <div class="otp-container">
                <p class="otp-code">{{ $code }}</p>
            </div>
            
            <p class="expiry">This code will expire in 15 minutes.</p>
            
            <div class="divider"></div>
            
            <div class="footer">
                <p>This is an automated security message. Please do not reply to this email.</p>
                <p>&copy; {{ date('Y') }} KindNet. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
