<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Welcome Onboard - MakeMyPayment</title>
</head>
<body style="margin:0;padding:0;background:#f0f2f8;font-family:'Segoe UI',Arial,sans-serif;">

@php
	$vanAccount = $van ?? ($user->merchantVirtualAccount ?? null);
@endphp

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
					<td style="background:linear-gradient(135deg,#2b3990 0%,#4158c0 58%,#6c3ec5 100%);
								border-radius:20px 20px 0 0;padding:44px 36px 38px;text-align:center;">
						<div style="display:inline-block;background:rgba(255,255,255,0.15);
									border-radius:50%;width:68px;height:68px;line-height:68px;
									font-size:32px;margin-bottom:18px;">
							✅
						</div>

						<h1 style="margin:0 0 10px;color:#ffffff;font-size:28px;font-weight:800;
								   letter-spacing:-0.4px;line-height:1.2;">
							Welcome Onboard,<br>{{ $user->full_name ?? 'Merchant' }}
						</h1>
						<p style="margin:0;color:rgba(255,255,255,0.88);font-size:15px;line-height:1.6;">
							Your merchant account is live and ready.<br>
							You can start making payments from your portal now.
						</p>
					</td>
				</tr>

				<tr>
					<td style="background:#ffffff;border-radius:0 0 20px 20px;
								padding:34px 36px 40px;">

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#f8f9fc;border-radius:14px;
									  border:1px solid #e8ecf5;padding:20px 22px;
									  margin-bottom:22px;">
							<tr>
								<td style="padding:0 0 10px;">
									<span style="font-size:12px;font-weight:700;
												 color:#6b7280;text-transform:uppercase;
												 letter-spacing:0.08em;">Merchant Snapshot</span>
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
							   style="background:linear-gradient(135deg,#eef4ff,#f4efff);
									  border:1px solid #dce4fb;border-radius:16px;
									  padding:20px 22px;margin-bottom:24px;">
							<tr>
								<td>
									<p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#2b3990;">
										VAN Account Details
									</p>
									<p style="margin:0 0 12px;font-size:12px;color:#5b6583;line-height:1.5;">
										Use this Virtual Account to add funds and process payouts.
									</p>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td style="font-size:12px;color:#6b7280;padding:4px 0;">Account Holder</td>
											<td style="font-size:12px;color:#1e2240;font-weight:700;text-align:right;padding:4px 0;">
												{{ $vanAccount->account_holder_name ?? ($user->full_name ?? 'N/A') }}
											</td>
										</tr>
										<tr>
											<td style="font-size:12px;color:#6b7280;padding:4px 0;">VAN Number</td>
											<td style="font-size:12px;color:#1e2240;font-weight:700;text-align:right;padding:4px 0;">
												{{ $vanAccount->van ?? 'N/A' }}
											</td>
										</tr>
										<tr>
											<td style="font-size:12px;color:#6b7280;padding:4px 0;">IFSC</td>
											<td style="font-size:12px;color:#1e2240;font-weight:700;text-align:right;padding:4px 0;">
												{{ $vanAccount->ifsc ?? 'N/A' }}
											</td>
										</tr>
										<tr>
											<td style="font-size:12px;color:#6b7280;padding:4px 0;">Bank Name</td>
											<td style="font-size:12px;color:#1e2240;font-weight:700;text-align:right;padding:4px 0;">
												{{ $vanAccount->bank_name ?? 'HDFC Bank' }}
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#fff7ed;border:1px solid #fed7aa;
									  border-radius:15px;padding:18px 20px;margin-bottom:24px;">
							<tr>
								<td>
									<p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#9a3412;">
										API Automation Is Pending Configuration
									</p>
									<p style="margin:0;font-size:13px;color:#7c2d12;line-height:1.65;">
										You can make payments from the portal right now.
										For API payments, please complete
										<strong>IP Whitelisting</strong>,
										<strong>Webhook Configuration</strong>, and account
										<strong>verification</strong> first. After approval,
										your setup will be fully automated.
									</p>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="margin-bottom:20px;">
							<tr>
								<td align="center">
									<a href="{{ route('merchant.settings') }}"
									   style="display:inline-block;background:linear-gradient(135deg,#2b3990,#6c3ec5);
											  color:#ffffff;text-decoration:none;
											  font-size:15px;font-weight:700;
											  padding:13px 34px;border-radius:50px;
											  letter-spacing:0.02em;margin:6px;">
										Configure IP &amp; Webhook
									</a>
									<a href="{{ route('merchant.dashboard') }}"
									   style="display:inline-block;background:#ffffff;
											  color:#2b3990;text-decoration:none;
											  font-size:14px;font-weight:700;
											  padding:12px 30px;border-radius:50px;
											  border:2px solid #2b3990;
											  letter-spacing:0.02em;margin:6px;">
										Go to Portal
									</a>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#fdf8ff;border:1px solid #ede5f9;
									  border-radius:14px;padding:16px 20px;">
							<tr>
								<td>
									<p style="margin:0;font-size:13px;color:#5b4280;line-height:1.6;">
										Need help with setup? Contact
										<a href="mailto:support@makemypayment.in"
										   style="color:#6c3ec5;font-weight:700;text-decoration:none;">
											support@makemypayment.in
										</a>
										and our onboarding team will assist you.
									</p>
								</td>
							</tr>
						</table>

					</td>
				</tr>

				<tr>
					<td align="center" style="padding:26px 0 8px;">
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
