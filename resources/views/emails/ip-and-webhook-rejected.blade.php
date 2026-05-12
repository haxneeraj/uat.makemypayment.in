<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>IP & Webhook Rejected - MakeMyPayment</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f8;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f8;padding:40px 16px;">
	<tr>
		<td align="center">
			<table width="100%" cellpadding="0" cellspacing="0" style="max-width:580px;">

				<tr>
					<td align="center" style="padding-bottom:28px;">
						<img src="https://makemypayment.in/makemypayment-logo.png"
							 alt="MakeMyPayment" height="44"
							 style="display:block;height:44px;width:auto;">
					</td>
				</tr>

				<tr>
					<td style="background:linear-gradient(135deg,#7f1d1d 0%,#b91c1c 60%,#dc2626 100%);
								border-radius:20px 20px 0 0;padding:42px 36px 34px;text-align:center;">
						<div style="display:inline-block;background:rgba(255,255,255,0.16);
									border-radius:50%;width:66px;height:66px;line-height:66px;
									font-size:30px;margin-bottom:16px;">
							⚠
						</div>

						<h1 style="margin:0 0 8px;color:#ffffff;font-size:26px;font-weight:800;line-height:1.2;">
							IP & Webhook Request Rejected
						</h1>
						<p style="margin:0;color:rgba(255,255,255,0.9);font-size:14px;line-height:1.6;">
							Hello {{ $user->full_name ?? 'Merchant' }}, your API activation request needs correction.
							Please review the details below and submit again.
						</p>
					</td>
				</tr>

				<tr>
					<td style="background:#ffffff;border-radius:0 0 20px 20px;padding:34px 36px 40px;">

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#f8fafc;border-radius:14px;border:1px solid #e5e7eb;
									  padding:18px 22px;margin-bottom:20px;">
							<tr>
								<td>
									<p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.08em;">
										Merchant Snapshot
									</p>
									<p style="margin:0;font-size:13px;color:#1f2937;line-height:1.8;">
										<strong>Name:</strong> {{ $user->full_name ?? '-' }}<br>
										<strong>Email:</strong> {{ $user->email ?? '-' }}<br>
										<strong>Merchant ID:</strong> {{ $user->merchant_id ?? '-' }}
									</p>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#fef2f2;border:1px solid #fecaca;border-radius:16px;
									  padding:18px 22px;margin-bottom:20px;">
							<tr>
								<td>
									<p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#991b1b;">Submitted Configuration</p>
									<p style="margin:0;font-size:13px;color:#7f1d1d;line-height:1.7;">
										<strong>IP:</strong> {{ $request->ip ?? 'N/A' }}<br>
										<strong>Webhook URL:</strong> {{ $request->webhook_url ?? 'N/A' }}<br>
										<strong>Webhook Secret:</strong> {{ $request->webhook_secret ?? 'N/A' }}
									</p>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#fff7ed;border:1px solid #fed7aa;border-radius:16px;
									  padding:18px 22px;margin-bottom:22px;">
							<tr>
								<td>
									<p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#9a3412;">Rejection Remark</p>
									<p style="margin:0;font-size:13px;color:#7c2d12;line-height:1.7;">
										{{ $request->remark ?: 'Please verify your submitted IP and webhook details, then submit your request again.' }}
									</p>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;
									  padding:16px 20px;margin-bottom:22px;">
							<tr>
								<td>
									<p style="margin:0;font-size:13px;color:#1e40af;line-height:1.7;">
										Update your API configuration from Settings, create fresh API credentials if required,
										and re-submit your IP and webhook request for approval.
									</p>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
							<tr>
								<td align="center">
									<a href="{{ route('merchant.settings') }}"
									   style="display:inline-block;background:linear-gradient(135deg,#b91c1c,#dc2626);
											  color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;
											  padding:13px 34px;border-radius:50px;letter-spacing:0.02em;margin:6px;">
										Fix & Re-submit Configuration
									</a>
									<a href="{{ route('merchant.dashboard') }}"
									   style="display:inline-block;background:#ffffff;color:#1f2937;
											  text-decoration:none;font-size:14px;font-weight:700;
											  padding:12px 30px;border-radius:50px;border:2px solid #d1d5db;
											  letter-spacing:0.02em;margin:6px;">
										Open Dashboard
									</a>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#fdf8ff;border:1px solid #ede5f9;border-radius:14px;padding:16px 20px;">
							<tr>
								<td>
									<p style="margin:0;font-size:13px;color:#5b4280;line-height:1.6;">
										Need help? Contact
										<a href="mailto:support@makemypayment.in"
										   style="color:#6c3ec5;font-weight:700;text-decoration:none;">
											support@makemypayment.in
										</a>
										and our team will help you complete onboarding quickly.
									</p>
								</td>
							</tr>
						</table>

					</td>
				</tr>

				<tr>
					<td align="center" style="padding:24px 0 8px;">
						<p style="margin:0 0 8px;font-size:12px;color:#9ca3af;">
							&copy; {{ date('Y') }} MMP Fintech Payment Solutions Pvt. Ltd. All rights reserved.
						</p>
						<p style="margin:0;font-size:12px;">
							<a href="{{ route('merchant.dashboard') }}" style="color:#6c3ec5;text-decoration:none;font-weight:600;">Portal</a>
							&nbsp;&bull;&nbsp;
							<a href="{{ route('merchant.settings') }}" style="color:#6c3ec5;text-decoration:none;font-weight:600;">Settings</a>
							&nbsp;&bull;&nbsp;
							<a href="mailto:support@makemypayment.in" style="color:#6c3ec5;text-decoration:none;font-weight:600;">Support</a>
						</p>
					</td>
				</tr>

			</table>
		</td>
	</tr>
</table>

</body>
</html>
