<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Payout Update - MakeMyPayment</title>
</head>
<body style="margin:0;padding:0;background:#f0f2f8;font-family:'Segoe UI',Arial,sans-serif;">

@php
	$status = (string) ($payout->status ?? 'pending');

	$statusLabel = match ($status) {
		'success' => 'SUCCESS',
		'failed' => 'FAILED',
		'processed' => 'PROCESSED',
		'send_to_bank' => 'SENT TO BANK',
		'initiated' => 'INITIATED',
		default => strtoupper(str_replace('_', ' ', $status)),
	};

	$statusColor = match ($status) {
		'success', 'processed' => '#047857',
		'failed' => '#b91c1c',
		'send_to_bank', 'initiated', 'pending' => '#1d4ed8',
		default => '#475569',
	};

	$statusBg = match ($status) {
		'success', 'processed' => '#ecfdf5',
		'failed' => '#fef2f2',
		'send_to_bank', 'initiated', 'pending' => '#eff6ff',
		default => '#f1f5f9',
	};
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
								border-radius:20px 20px 0 0;padding:42px 36px 34px;text-align:center;">
						<div style="display:inline-block;background:rgba(255,255,255,0.16);
									border-radius:50%;width:66px;height:66px;line-height:66px;
									font-size:30px;margin-bottom:16px;">
							💸
						</div>

						<h1 style="margin:0 0 8px;color:#ffffff;font-size:27px;font-weight:800;line-height:1.2;">
							Payout Transaction Update
						</h1>
						<p style="margin:0;color:rgba(255,255,255,0.9);font-size:14px;line-height:1.6;">
							Hello {{ $user->full_name ?? 'Merchant' }},
							your payout request has been processed. Please review the latest status below.
						</p>
					</td>
				</tr>

				<tr>
					<td style="background:#ffffff;border-radius:0 0 20px 20px;padding:34px 36px 40px;">

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="margin-bottom:20px;background:{{ $statusBg }};border:1px solid {{ $statusColor }}33;
									  border-radius:14px;padding:14px 18px;">
							<tr>
								<td style="font-size:12px;color:#64748b;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;">
									Current Status
								</td>
								<td align="right">
									<span style="display:inline-block;background:#ffffff;border:1px solid {{ $statusColor }}44;
												 color:{{ $statusColor }};padding:6px 12px;border-radius:999px;
												 font-size:11px;font-weight:800;letter-spacing:0.06em;">
										{{ $statusLabel }}
									</span>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:linear-gradient(135deg,#ecfeff,#eef2ff);border:1px solid #c7d2fe;
									  border-radius:16px;padding:20px 22px;margin-bottom:22px;">
							<tr>
								<td align="center">
									<p style="margin:0 0 6px;font-size:12px;font-weight:700;color:#4c1d95;text-transform:uppercase;letter-spacing:0.08em;">
										Payout Amount
									</p>
									<p style="margin:0;font-size:32px;font-weight:800;color:#0f172a;line-height:1.1;">
										&#8377;{{ number_format((float) ($payout->amount ?? 0), 2) }}
									</p>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#f8f9fc;border-radius:14px;border:1px solid #e9ecf5;
									  padding:20px 22px;margin-bottom:22px;">
							<tr>
								<td style="padding:0 0 10px;">
									<span style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.08em;">
										Transaction Details
									</span>
								</td>
							</tr>
							<tr>
								<td>
									<table width="100%" cellpadding="0" cellspacing="0">
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">Transaction ID</td>
											<td style="font-size:13px;color:#1e2240;font-weight:700;text-align:right;padding:5px 0;">
												{{ $payout->transaction_id ?? 'N/A' }}
											</td>
										</tr>
										<tr><td colspan="2" style="height:1px;background:#eceef5;padding:0;"></td></tr>
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">Beneficiary</td>
											<td style="font-size:13px;color:#1e2240;font-weight:600;text-align:right;padding:5px 0;">
												{{ $payout->account_holder ?? 'N/A' }}
											</td>
										</tr>
										<tr><td colspan="2" style="height:1px;background:#eceef5;padding:0;"></td></tr>
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">Account Number</td>
											<td style="font-size:13px;color:#1e2240;font-weight:600;text-align:right;padding:5px 0;">
												{{ $payout->account_number ?? 'N/A' }}
											</td>
										</tr>
										<tr><td colspan="2" style="height:1px;background:#eceef5;padding:0;"></td></tr>
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">IFSC</td>
											<td style="font-size:13px;color:#1e2240;font-weight:600;text-align:right;padding:5px 0;">
												{{ $payout->ifsc_code ?? 'N/A' }}
											</td>
										</tr>
										<tr><td colspan="2" style="height:1px;background:#eceef5;padding:0;"></td></tr>
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">Mode</td>
											<td style="font-size:13px;color:#1e2240;font-weight:700;text-align:right;padding:5px 0;">
												{{ strtoupper((string) ($payout->mode ?? 'N/A')) }}
											</td>
										</tr>
										<tr><td colspan="2" style="height:1px;background:#eceef5;padding:0;"></td></tr>
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">Fee</td>
											<td style="font-size:13px;color:#1e2240;font-weight:600;text-align:right;padding:5px 0;">
												&#8377;{{ number_format((float) ($payout->fee ?? 0), 2) }}
											</td>
										</tr>
										<tr><td colspan="2" style="height:1px;background:#eceef5;padding:0;"></td></tr>
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">Total Debit</td>
											<td style="font-size:13px;color:#1e2240;font-weight:700;text-align:right;padding:5px 0;">
												&#8377;{{ number_format((float) ($payout->total_amount ?? 0), 2) }}
											</td>
										</tr>
										<tr><td colspan="2" style="height:1px;background:#eceef5;padding:0;"></td></tr>
										<tr>
											<td style="font-size:13px;color:#6b7280;padding:5px 0;">UTR</td>
											<td style="font-size:13px;color:#1e2240;font-weight:600;text-align:right;padding:5px 0;">
												{{ $payout->utr ?: 'Pending' }}
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
										This is an automated transaction update. If status is pending/initiated, it may update shortly.
										Please track final reconciliation from your Payouts and Reports screen.
									</p>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
							<tr>
								<td align="center">
									<a href="{{ route('merchant.payouts') }}"
									   style="display:inline-block;background:linear-gradient(135deg,#2b3990,#6c3ec5);
											  color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;
											  padding:13px 34px;border-radius:50px;letter-spacing:0.02em;margin:6px;">
										View Payouts
									</a>
									<a href="{{ route('merchant.reports') }}"
									   style="display:inline-block;background:#ffffff;color:#2b3990;
											  text-decoration:none;font-size:14px;font-weight:700;
											  padding:12px 30px;border-radius:50px;border:2px solid #2b3990;
											  letter-spacing:0.02em;margin:6px;">
										View Reports
									</a>
								</td>
							</tr>
						</table>

						<table width="100%" cellpadding="0" cellspacing="0"
							   style="background:#fdf8ff;border:1px solid #ede5f9;border-radius:14px;padding:16px 20px;">
							<tr>
								<td>
									<p style="margin:0;font-size:13px;color:#5b4280;line-height:1.6;">
										Need support? Contact
										<a href="mailto:support@makemypayment.in"
										   style="color:#6c3ec5;font-weight:700;text-decoration:none;">
											support@makemypayment.in
										</a>
										and our team will assist you.
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
							<a href="{{ route('merchant.payouts') }}" style="color:#6c3ec5;text-decoration:none;font-weight:600;">Payouts</a>
							&nbsp;&bull;&nbsp;
							<a href="{{ route('merchant.reports') }}" style="color:#6c3ec5;text-decoration:none;font-weight:600;">Reports</a>
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
