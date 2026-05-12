# Introduction



<aside>
    <strong>Base URL</strong>: <code>https://api-uat.makemypayment.in</code>
</aside>

# Getting Started With Your API

This guide helps you get started with the Payment System API integration.


## API Base URL

We provide separate base URLs for two environments:

- **UAT (Testing)**: https://api-uat.makemypayment.in  
Use this environment to test your API calls, integrations, and development workflow.

- **Production**: https://api.makemypayment.in  
Use this environment to interact with live data in production.

> **Recommended Steps:**
> 1. Start with the **UAT** base URL to explore the API and test requests.  
> 2. Once your integration works as expected, switch to the **Production** base URL.



## Key Features
- ✅ **Initiate Payout** – Initiate a payout for your account.
- 🔎 **Check Payout Status** – Track the status of any payout in real time.
- 💰 **Retrieve Account Balances** – Get your current available balance instantly.



## 🔐 Authentication
All endpoints require **authentication** using two credentials:
- `X-API-KEY` – Your unique API key.
- `X-API-SECRET` – Used with **AES encryption** for secure request signing.

Every payout and balance request is validated in this order:
1. API key and secret match a merchant account.
2. Merchant status must be `active`.
3. `kyc_status` and `van_status` must be `verified`.
4. Merchant callback/IP activation must be `verified`, with webhook URL/secret set.
5. Caller IP must match the merchant's whitelisted IP.

> If any validation fails, the API returns an error response in the same response envelope format.



## 🔒 Encryption
All sensitive request data is encrypted using **AES-256-CBC**.  
- **Algorithm:** AES-256-CBC  
- **Default IV:** `0g7H#8X2mTqjvLwR`

Ensure your client application implements the **same encryption and decryption logic** before sending the payload. The API expects the payload to be **base64 encoded** after encryption.

Example workflow:
1. Prepare your request data as JSON.
2. Encrypt using AES-256-CBC with your `api_secret` and the default IV.
3. Base64 encode the encrypted string.
4. Send it in the request body or as defined by the endpoint.



## 💡 Tip for Developers
Use the provided code examples (on the right in desktop view or below on mobile) to quickly test requests in your preferred programming language.  
Following the examples ensures correct headers, encryption, and payload structure.



---

# Webhook Integration Guide

When a payout status changes (e.g., `processing` → `success` or `failed`), the system automatically sends a **POST request** to your registered **Webhook URL**.  
You must implement this endpoint on your server to receive real-time payout status updates.



## How It Works

1. You register a **Webhook URL** and a **Webhook Secret** during IP/callback activation.
2. When a payout status changes, the system encrypts the payload and POSTs it to your URL.
3. Your endpoint must **respond with HTTP 200** to acknowledge receipt.
4. If your server does not return `200`, the system will **retry up to 5 times** at the following intervals:
   - 1 minute, 5 minutes, 15 minutes (×3 remaining retries)



## Incoming Request Format

Your webhook URL will receive a **POST** request with:

| Header | Value |
|---|---|
| `Content-Type` | `application/json` |
| `x-signature` | Your registered Webhook Secret |

The **raw request body** is an **AES-256-CBC encrypted, Base64-encoded string** — not a JSON object.



## Decrypting the Payload

To read the webhook data, decrypt the raw body using:

- **Algorithm:** AES-256-CBC
- **Key:** Your `api_secret` (the same secret used to sign API requests)
- **IV:** `0g7H#8X2mTqjvLwR` (fixed)
- **Step 1:** Base64-decode the raw body
- **Step 2:** Decrypt using AES-256-CBC

After decryption, you get a JSON string with the following fields:

```json
{
  "transaction_id": "TXN987654321",
  "beneficiary_account_holder": "John Doe",
  "beneficiary_account_number": "1234567890",
  "beneficiary_bank_name": "HDFC Bank",
  "beneficiary_ifsc_code": "HDFC0001234",
  "amount": 500,
  "status": "success",
  "utr": "UTR1234567890",
  "remarks": "Payment for services",
  "narration": "Salary"
}
```

### Payload Field Reference

| Field | Type | Description |
|---|---|---|
| `transaction_id` | string | Your unique payout transaction ID |
| `beneficiary_account_holder` | string | Beneficiary's full name |
| `beneficiary_account_number` | string | Beneficiary's bank account number |
| `beneficiary_bank_name` | string | Beneficiary's bank name |
| `beneficiary_ifsc_code` | string | Beneficiary's bank IFSC code |
| `amount` | number | Payout amount in INR |
| `status` | string | Current payout status: `pending`, `processing`, `success`, `failed` |
| `utr` | string\|null | UTR number (available on success) |
| `remarks` | string\|null | Remarks for the payout |
| `narration` | string\|null | Narration for the transaction |



## Verifying the Request (Recommended)

Before processing, verify that the request is genuinely from our system by checking the `x-signature` header against your registered Webhook Secret.



## Implementation Examples

### PHP

```php
<?php

// Webhook endpoint: POST https://your-domain.com/webhook/payout

$api_secret  = 'YOUR_API_SECRET';   // same as your api_secret
$webhook_secret = 'YOUR_WEBHOOK_SECRET';
$iv = '0g7H#8X2mTqjvLwR';

// 1. Verify signature
$signature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';
if ($signature !== $webhook_secret) {
    http_response_code(401);
    exit('Unauthorized');
}

// 2. Read and decrypt body
$raw_body  = file_get_contents('php://input');
$decrypted = openssl_decrypt(
    base64_decode($raw_body),
    'AES-256-CBC',
    $api_secret,
    OPENSSL_RAW_DATA,
    $iv
);

$payload = json_decode($decrypted, true);

// 3. Process the payload
$transaction_id = $payload['transaction_id'];
$status         = $payload['status'];
$utr            = $payload['utr'] ?? null;

// ... update your database, notify user, etc.

// 4. MUST return HTTP 200
http_response_code(200);
echo json_encode(['received' => true]);
```

### Node.js (Express)

```javascript
const express = require('express');
const crypto  = require('crypto');
const app     = express();

const API_SECRET     = 'YOUR_API_SECRET';
const WEBHOOK_SECRET = 'YOUR_WEBHOOK_SECRET';
const IV             = '0g7H#8X2mTqjvLwR';

app.post('/webhook/payout', express.text({ type: '*/*' }), (req, res) => {
  // 1. Verify signature
  if (req.headers['x-signature'] !== WEBHOOK_SECRET) {
    return res.status(401).send('Unauthorized');
  }

  // 2. Decrypt body
  const decipher = crypto.createDecipheriv(
    'aes-256-cbc',
    Buffer.from(API_SECRET),
    Buffer.from(IV)
  );
  let decrypted = decipher.update(Buffer.from(req.body, 'base64'));
  decrypted = Buffer.concat([decrypted, decipher.final()]);
  const payload = JSON.parse(decrypted.toString());

  // 3. Process
  console.log('Payout update:', payload.transaction_id, payload.status);

  // 4. MUST return HTTP 200
  res.status(200).json({ received: true });
});
```

> ⚠️ **Important:** Your webhook endpoint **must return HTTP 200** within the timeout window.  
> Any other status code (including 201, 204, 4xx, 5xx) will be treated as a failure and trigger a retry.

