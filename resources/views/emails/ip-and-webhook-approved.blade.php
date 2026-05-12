<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>IP & Webhook Approved - MakeMyPayment</title>
</head>
<body style="margin:0;padding:0;background:#f0f2f8;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f8;padding:40px 16px;">
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
					<td style="background:linear-gradient(135deg,#065f46 0%,#059669 60%,#10b981 100%);
								border-radius:20px 20px 0 0;padding:44px 36px 38px;text-align:center;">
						<div style="display:inline-block;background:rgba(255,255,255,0.16);
									border-radius:50%;width:68px;height:68px;line-height:68px;
									font-size:32px;margin-bottom:18px;">
							🚀
						</div>

						<h1 style="margin:0 0 10px;color:#ffffff;font-size:28px;font-weight:800;
								   letter-spacing:-0.4px;line-height:1.2;">
							API Access Approved,
							{{ $user->full_name ?? 'Merchant' }}
						</h1>
						<p style="margin:0;color:rgba(255,255,255,0.9);font-size:15px;line-height:1.6;">
							Your IP and webhook configuration has been approved.
							API-based payout automation is now active.
						</p>
					</td>
				</tr>

				<tr>
					<td style="background:#ffffff;border-radius:0 0 20px 20px;padding:34px 36px 40px;">

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#f8f9fc;border-radius:14px;border:1px solid #e9ecf5;
									  padding:20px 22px;margin-bottom:22px;">
							<tr>
								<td style="padding:0 0 10px;">
									<span style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.08em;">
										Merchant Snapshot
									</span>
								</td>
							</tr>
							<tr>
								<td>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">Name</td>
											<td style="font-size:13px;color:#1e2240;font-weight:600;text-align:right;padding:5px 0;">
												{{ $user->full_name ?? '-' }}
											</td>
										</tr>
										<tr>
											<td colspan="2" style="height:1px;background:#eceef5;padding:0;"></td>
										</tr>
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">Email</td>
											<td style="font-size:13px;color:#1e2240;font-weight:600;text-align:right;padding:5px 0;">
												{{ $user->email ?? '-' }}
											</td>
										</tr>
										<tr>
											<td colspan="2" style="height:1px;background:#eceef5;padding:0;"></td>
										</tr>
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">Merchant ID</td>
											<td style="font-size:13px;color:#2b3990;font-weight:700;text-align:right;padding:5px 0;">
												{{ $user->merchant_id ?? '-' }}
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:linear-gradient(135deg,#ecfdf5,#e6fffb);border:1px solid #a7f3d0;
									  border-radius:16px;padding:20px 22px;margin-bottom:24px;">
							<tr>
								<td>
									<p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#065f46;">Approved Integration Details</p>
									<p style="margin:0 0 12px;font-size:12px;color:#0f766e;line-height:1.5;">
										These settings are now active for your API callbacks and security checks.
									</p>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td style="font-size:12px;color:#4b5563;padding:4px 0;">Whitelisted IP</td>
											<td style="font-size:12px;color:#111827;font-weight:700;text-align:right;padding:4px 0;">
												{{ $request->ip ?? 'N/A' }}
											</td>
										</tr>
										<tr>
											<td style="font-size:12px;color:#4b5563;padding:4px 0;">Webhook URL</td>
											<td style="font-size:12px;color:#111827;font-weight:700;text-align:right;padding:4px 0;">
												{{ $request->webhook_url ?? 'N/A' }}
											</td>
										</tr>
										<tr>
											<td style="font-size:12px;color:#4b5563;padding:4px 0;">Webhook Secret</td>
											<td style="font-size:12px;color:#111827;font-weight:700;text-align:right;padding:4px 0;">
												{{ $request->webhook_secret ?? 'N/A' }}
											</td>
										</tr>
										<tr>
											<td style="font-size:12px;color:#4b5563;padding:4px 0;">Status</td>
											<td style="font-size:12px;color:#065f46;font-weight:800;text-align:right;padding:4px 0;">
												Approved
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:14px;
									  padding:16px 20px;margin-bottom:22px;">
							<tr>
								<td>
									<p style="margin:0;font-size:13px;color:#1e40af;line-height:1.7;">
										You can continue payouts from the portal and now also process payouts through API.
										Please create your API credentials from Settings and start doing payments.
										Keep your webhook endpoint stable to receive instant transaction updates.
									</p>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
							<tr>
								<td align="center">
									<a href="{{ route('merchant.settings') }}"
									   style="display:inline-block;background:linear-gradient(135deg,#2b3990,#6c3ec5);
											  color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;
											  padding:13px 34px;border-radius:50px;letter-spacing:0.02em;margin:6px;">
										Review API Settings
									</a>
									<a href="{{ route('merchant.dashboard') }}"
									   style="display:inline-block;background:#ffffff;color:#2b3990;
											  text-decoration:none;font-size:14px;font-weight:700;
											  padding:12px 30px;border-radius:50px;border:2px solid #2b3990;
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
										Need integration support? Contact
										<a href="mailto:support@makemypayment.in"
										   style="color:#6c3ec5;font-weight:700;text-decoration:none;">
											support@makemypayment.in
										</a>
										and our team will help you with API onboarding.
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
