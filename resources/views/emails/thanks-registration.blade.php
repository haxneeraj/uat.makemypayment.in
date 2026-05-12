<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MakeMyPayment</title>
</head>
<body style="margin:0;padding:0;background:#f0f2f8;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f8;padding:40px 16px;">
    <tr>
        <td align="center">
            <table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px;">

                {{-- ── Logo header ── --}}
                <tr>
                    <td align="center" style="padding-bottom:28px;">
                        <img src="https://makemypayment.in/makemypayment-logo.png"
                             alt="MakeMyPayment" height="44"
                             style="display:block;height:44px;width:auto;">
                    </td>
                </tr>

                {{-- ── Hero card ── --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#2b3990 0%,#4158c0 60%,#6c3ec5 100%);
                                border-radius:20px 20px 0 0;padding:48px 40px 40px;text-align:center;">

                        {{-- Celebration icon --}}
                        <div style="display:inline-block;background:rgba(255,255,255,0.15);
                                    border-radius:50%;width:72px;height:72px;line-height:72px;
                                    font-size:36px;margin-bottom:24px;">
                            🎉
                        </div>

                        <h1 style="margin:0 0 10px;color:#ffffff;font-size:28px;font-weight:800;
                                   letter-spacing:-0.5px;line-height:1.2;">
                            Welcome aboard,<br>{{ $user->full_name }}!
                        </h1>
                        <p style="margin:0;color:rgba(255,255,255,0.85);font-size:15px;line-height:1.6;">
                            Your MakeMyPayment merchant account is ready.<br>
                            Start sending payouts in minutes.
                        </p>
                    </td>
                </tr>

                {{-- ── White body ── --}}
                <tr>
                    <td style="background:#ffffff;border-radius:0 0 20px 20px;
                                padding:36px 40px 40px;">

                        {{-- Account summary chips --}}
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="background:#f8f9fc;border-radius:14px;
                                      border:1px solid #e9ecf5;padding:20px 24px;
                                      margin-bottom:32px;">
                            <tr>
                                <td style="padding:6px 0;">
                                    <span style="font-size:12px;font-weight:700;
                                                 color:#6b7280;text-transform:uppercase;
                                                 letter-spacing:0.08em;">Account Details</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0 0;">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="font-size:13px;color:#6b7280;padding:5px 0;">
                                                Name
                                            </td>
                                            <td style="font-size:13px;font-weight:600;
                                                       color:#1e2240;text-align:right;padding:5px 0;">
                                                {{ $user->full_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"
                                                style="height:1px;background:#eceef5;padding:0;"></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;color:#6b7280;padding:5px 0;">
                                                Email
                                            </td>
                                            <td style="font-size:13px;font-weight:600;
                                                       color:#1e2240;text-align:right;padding:5px 0;">
                                                {{ $user->email }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"
                                                style="height:1px;background:#eceef5;padding:0;"></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:13px;color:#6b7280;padding:5px 0;">
                                                Merchant ID
                                            </td>
                                            <td style="font-size:13px;font-weight:700;
                                                       color:#2b3990;text-align:right;padding:5px 0;">
                                                {{ $user->merchant_id }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        {{-- ── KYC Onboarding Alert ── --}}
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="background:linear-gradient(135deg,#fff7ed,#fef3c7);
                                      border:1px solid #fcd34d;border-radius:16px;
                                      padding:20px 24px;margin-bottom:28px;">
                            <tr>
                                <td>
                                    <p style="margin:0 0 4px;font-size:15px;font-weight:800;
                                              color:#92400e;">
                                        &#9888;&#65039; Start Your Payout Onboarding
                                    </p>
                                    <p style="margin:0 0 14px;font-size:13px;color:#78350f;
                                              line-height:1.6;">
                                        To activate payouts on your account, you need to complete
                                        the <strong>KYC (Know Your Customer)</strong> verification process.
                                        Our team will review your documents and approve your account
                                        within <strong>1&ndash;7 business days</strong>.
                                    </p>
                                    <a href="{{ route('merchant.kyc') }}"
                                       style="display:inline-block;background:#d97706;
                                              color:#ffffff;text-decoration:none;
                                              font-size:13px;font-weight:700;
                                              padding:10px 28px;border-radius:50px;">
                                        Start KYC Onboarding &rarr;
                                    </a>
                                </td>
                            </tr>
                        </table>

                        {{-- Next steps --}}
                        <p style="margin:0 0 16px;font-size:14px;font-weight:700;
                                  color:#1e2240;text-transform:uppercase;letter-spacing:0.08em;">
                            Get started in 3 steps
                        </p>

                        {{-- Step 1 --}}
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="margin-bottom:12px;">
                            <tr>
                                <td width="40" valign="top"
                                    style="padding-top:2px;">
                                    <div style="width:32px;height:32px;border-radius:50%;
                                                background:#eef0fb;color:#2b3990;
                                                font-size:13px;font-weight:800;
                                                text-align:center;line-height:32px;">1</div>
                                </td>
                                <td valign="top" style="padding-left:4px;">
                                    <p style="margin:0;font-size:14px;font-weight:700;
                                              color:#1e2240;">
                                        Complete KYC &amp; Onboarding
                                        <a href="{{ route('merchant.kyc') }}"
                                           style="margin-left:8px;font-size:11px;font-weight:700;
                                                  color:#6c3ec5;text-decoration:none;
                                                  background:#f3eeff;padding:2px 10px;
                                                  border-radius:20px;">
                                            Start Now &rarr;
                                        </a>
                                    </p>
                                    <p style="margin:4px 0 0;font-size:13px;color:#6b7280;
                                              line-height:1.5;">
                                        Submit your business documents. Verification takes
                                        <strong style="color:#1e2240;">1&ndash;7 business days</strong>
                                        after submission.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- Step 2 --}}
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="margin-bottom:12px;">
                            <tr>
                                <td width="40" valign="top" style="padding-top:2px;">
                                    <div style="width:32px;height:32px;border-radius:50%;
                                                background:#eef0fb;color:#2b3990;
                                                font-size:13px;font-weight:800;
                                                text-align:center;line-height:32px;">2</div>
                                </td>
                                <td valign="top" style="padding-left:4px;">
                                    <p style="margin:0;font-size:14px;font-weight:700;
                                              color:#1e2240;">Add funds to your virtual account number</p>
                                    <p style="margin:4px 0 0;font-size:13px;color:#6b7280;
                                              line-height:1.5;">
                                        Transfer funds to your Virtual Account Number (VAN)
                                        to start processing payouts.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- Step 3 --}}
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="margin-bottom:32px;">
                            <tr>
                                <td width="40" valign="top" style="padding-top:2px;">
                                    <div style="width:32px;height:32px;border-radius:50%;
                                                background:#eef0fb;color:#2b3990;
                                                font-size:13px;font-weight:800;
                                                text-align:center;line-height:32px;">3</div>
                                </td>
                                <td valign="top" style="padding-left:4px;">
                                    <p style="margin:0;font-size:14px;font-weight:700;
                                              color:#1e2240;">Send your first payout</p>
                                    <p style="margin:4px 0 0;font-size:13px;color:#6b7280;
                                              line-height:1.5;">
                                        Use our dashboard or API to send instant payouts
                                        to any bank account across India.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- CTA buttons --}}
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="margin-bottom:32px;">
                            <tr>
                                <td align="center">
                                    {{-- Primary: KYC --}}
                                    <a href="{{ route('merchant.kyc') }}"
                                       style="display:inline-block;background:linear-gradient(135deg,#2b3990,#6c3ec5);
                                              color:#ffffff;text-decoration:none;
                                              font-size:15px;font-weight:700;
                                              padding:14px 36px;border-radius:50px;
                                              letter-spacing:0.02em;margin:6px;">
                                        Start KYC Onboarding &rarr;
                                    </a>
                                    {{-- Secondary: Login / Portal --}}
                                    <a href="{{ route('login') }}"
                                       style="display:inline-block;background:#ffffff;
                                              color:#2b3990;text-decoration:none;
                                              font-size:14px;font-weight:700;
                                              padding:13px 32px;border-radius:50px;
                                              border:2px solid #2b3990;
                                              letter-spacing:0.02em;margin:6px;">
                                        Login to Portal
                                    </a>
                                </td>
                            </tr>
                        </table>

                        {{-- Portal / Login quick links --}}
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="background:#f0f4ff;border:1px solid #d4dcf9;
                                      border-radius:14px;padding:16px 20px;
                                      margin-bottom:16px;">
                            <tr>
                                <td>
                                    <p style="margin:0 0 6px;font-size:13px;font-weight:700;
                                              color:#2b3990;">&#128279; Quick Links</p>
                                    <p style="margin:0;font-size:13px;color:#374151;line-height:2;">
                                        &#128274;&nbsp;
                                        <a href="{{ route('login') }}"
                                           style="color:#2b3990;font-weight:600;
                                                  text-decoration:none;">
                                            Merchant Portal Login
                                        </a><br>
                                        &#128203;&nbsp;
                                        <a href="{{ route('merchant.kyc') }}"
                                           style="color:#6c3ec5;font-weight:600;
                                                  text-decoration:none;">
                                            KYC / Onboarding Form
                                        </a><br>
                                        &#128218;&nbsp;
                                        <a href="https://developer.makemypayment.in"
                                           style="color:#6c3ec5;font-weight:600;
                                                  text-decoration:none;">
                                            API Documentation
                                        </a>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        {{-- Support note --}}
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="background:#fdf8ff;border:1px solid #ede5f9;
                                      border-radius:14px;padding:16px 20px;">
                            <tr>
                                <td>
                                    <p style="margin:0;font-size:13px;color:#5b4280;
                                              line-height:1.6;">
                                        <strong>Need help?</strong> Our team is here for you.
                                        Reach us anytime at
                                        <a href="mailto:support@makemypayment.in"
                                           style="color:#6c3ec5;font-weight:600;
                                                  text-decoration:none;">
                                            support@makemypayment.in
                                        </a>.
                                        Verification typically completes in
                                        <strong style="color:#2b3990;">1&ndash;7 business days</strong>
                                        after your KYC submission.
                                    </p>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                {{-- ── Footer ── --}}
                <tr>
                    <td align="center" style="padding:28px 0 8px;">
                        <p style="margin:0 0 8px;font-size:12px;color:#9ca3af;">
                            &copy; {{ date('Y') }} MMP Fintech Payment Solutions Pvt. Ltd.
                            All rights reserved.
                        </p>
                        <p style="margin:0;font-size:12px;">
                            <a href="{{ route('login') }}"
                               style="color:#6c3ec5;text-decoration:none;font-weight:600;">
                                Login
                            </a>
                            &nbsp;&bull;&nbsp;
                            <a href="{{ route('merchant.kyc') }}"
                               style="color:#6c3ec5;text-decoration:none;font-weight:600;">
                                KYC Onboarding
                            </a>
                            &nbsp;&bull;&nbsp;
                            <a href="mailto:support@makemypayment.in"
                               style="color:#6c3ec5;text-decoration:none;font-weight:600;">
                                Support
                            </a>
                            &nbsp;&bull;&nbsp;
                            <a href="https://developer.makemypayment.in"
                               style="color:#6c3ec5;text-decoration:none;font-weight:600;">
                                API Docs
                            </a>
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
