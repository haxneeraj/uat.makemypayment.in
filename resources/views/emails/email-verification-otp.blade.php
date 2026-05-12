<!DOCTYPE html>
<html lang="en" style="background:#f6f8fc;">
<head>
    <meta charset="UTF-8">
    <title>Email Verification - MakeMyPayment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { background: #f6f8fc; font-family: 'Inter', Arial, sans-serif; color: #222; margin:0; padding:0; }
        .container { max-width: 480px; margin: 40px auto; background: #fff; border-radius: 18px; box-shadow: 0 4px 32px rgba(0,0,0,0.07); padding: 40px 32px; }
        .logo { display: block; margin: 0 auto 24px; }
        .heading { color: #0b2241; font-size: 1.5rem; font-weight: 700; margin-bottom: 8px; letter-spacing: -0.5px; }
        .subheading { color: #4f5e6b; font-size: 1rem; margin-bottom: 32px; }
        .otp-box { background: #f6f8fc; border-radius: 12px; padding: 24px 0; text-align: center; margin-bottom: 32px; }
        .otp { font-size: 2.5rem; color: #0ea5e9; font-weight: bold; letter-spacing: 0.5rem; }
        .btn { display:inline-block; background:#0ea5e9; color:#fff; font-weight:600; padding:12px 32px; border-radius:8px; text-decoration:none; margin-top:20px; font-size:1rem;}
        .footer { color: #aaa; font-size: 0.9rem; text-align: center; margin-top: 40px; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 32px 0; }
        @media (max-width:600px) {
            .container { padding: 24px 8px; }
            .logo { height: 48px !important; }
        }
    </style>
</head>
<body>
    <div class="container">
        <img class="logo" src="{{ asset('makemypayment-logo.svg') }}" alt="MakeMyPayment" height="56">
        <div class="heading">Verify your email address</div>
        <div class="subheading">
            Please use the following OTP code to verify your email address and activate your MakeMyPayment account.
        </div>
        <div class="otp-box">
            <span class="otp">{{ $data['otp'] ?? '123456' }}</span>
        </div>
        <div style="color:#4f5e6b;font-size:1rem;">
            This code is valid for <b>10 minutes</b>.<br>
            If you did not request this, you can safely ignore this email.
        </div>
        <hr class="divider">
        <div class="footer">
            &copy; {{ date('Y') }} MakeMyPayment. All rights reserved.<br>
            <span style="color:#0ea5e9;">India's trusted payment solution.</span>
        </div>
    </div>
</body>
</html>
