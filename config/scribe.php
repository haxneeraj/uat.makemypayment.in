<?php

use Knuckles\Scribe\Extracting\Strategies;
use Knuckles\Scribe\Config\Defaults;
use Knuckles\Scribe\Config\AuthIn;
use function Knuckles\Scribe\Config\{removeStrategies, configureStrategy};

// Only the most common configs are shown. See the https://scribe.knuckles.wtf/laravel/reference/config for all.

return [
    // The HTML <title> for the generated documentation.
    'title' => config('app.name') . ' API Documentation',

    // A short description of your API. Will be included in the docs webpage, Postman collection and OpenAPI spec.
    'description' => '',

    // Text to place in the "Introduction" section, right after the `description`. Markdown and HTML are supported.
    'intro_text' => <<<'INTRO'
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
        INTRO,



    // The base URL displayed in the docs.
    // If you're using `laravel` type, you can set this to a dynamic string, like '{{ config("app.tenant_url") }}' to get a dynamic base URL.
    'base_url' => 'https://api-uat.makemypayment.in',

    // Routes to include in the docs
    'routes' => [
        [
            'match' => [
                // Match only routes whose paths match this pattern (use * as a wildcard to match any characters). Example: 'users/*'.
                'prefixes' => ['api/*'],

                // Match only routes whose domains match this pattern (use * as a wildcard to match any characters). Example: 'api.*'.
                'domains' => ['*'],
            ],

            // Include these routes even if they did not match the rules above.
            'include' => [
                // 'users.index', 'POST /new', '/auth/*'
            ],

            // Exclude these routes even if they matched the rules above.
            'exclude' => [
                // Internal-use only routes — not exposed in public docs
                'POST api/v1/webhook',
                'POST api/v1/callback/springnxt-2fa',
                'POST api/v1/callback/sprintnxt-payout',
            ],
        ],
    ],

    // The type of documentation output to generate.
    // - "static" will generate a static HTMl page in the /public/docs folder,
    // - "laravel" will generate the documentation as a Blade view, so you can add routing and authentication.
    // - "external_static" and "external_laravel" do the same as above, but pass the OpenAPI spec as a URL to an external UI template
    'type' => 'static',

    // See https://scribe.knuckles.wtf/laravel/reference/config#theme for supported options
    'theme' => 'default',

    'static' => [
        // HTML documentation, assets and Postman collection will be generated to this folder.
        // Source Markdown will still be in resources/docs.
        'output_path' => 'public/docs',
    ],

    'laravel' => [
        // Whether to automatically create a docs route for you to view your generated docs. You can still set up routing manually.
        'add_routes' => false,

        // URL path to use for the docs endpoint (if `add_routes` is true).
        // By default, `/docs` opens the HTML page, `/docs.postman` opens the Postman collection, and `/docs.openapi` the OpenAPI spec.
        'docs_url' => '/docs',

        // Directory within `public` in which to store CSS and JS assets.
        // By default, assets are stored in `public/vendor/scribe`.
        // If set, assets will be stored in `public/{{assets_directory}}`
        'assets_directory' => null,

        // Middleware to attach to the docs endpoint (if `add_routes` is true).
        'middleware' => [],
    ],

    'external' => [
        'html_attributes' => []
    ],

    'try_it_out' => [
        // Add a Try It Out button to your endpoints so consumers can test endpoints right from their browser.
        // Don't forget to enable CORS headers for your endpoints.
        'enabled' => true,

        // The base URL to use in the API tester. Leave as null to be the same as the displayed URL (`scribe.base_url`).
        'base_url' => null,

        // [Laravel Sanctum] Fetch a CSRF token before each request, and add it as an X-XSRF-TOKEN header.
        'use_csrf' => false,

        // The URL to fetch the CSRF token from (if `use_csrf` is true).
        'csrf_url' => '/sanctum/csrf-cookie',
    ],

    // How is your API authenticated? This information will be used in the displayed docs, generated examples and response calls.
    'auth' => [
        // Set this to true if ANY endpoints in your API use authentication.
        'enabled' => true,

        // Set this to true if your API should be authenticated by default. If so, you must also set `enabled` (above) to true.
        // You can then use @unauthenticated or @authenticated on individual endpoints to change their status from the default.
        'default' => false,

        // Where is the auth value meant to be sent in a request?
        'in' => AuthIn::HEADER->value,

        // The name of the auth parameter (e.g. token, key, apiKey) or header (e.g. Authorization, Api-Key).
        'name' => 'X-API-KEY',

        // The value of the parameter to be used by Scribe to authenticate response calls.
        // This will NOT be included in the generated documentation. If empty, Scribe will use a random value.
        'use_value' => env('SCRIBE_AUTH_KEY'),

        // Placeholder your users will see for the auth parameter in the example requests.
        // Set this to null if you want Scribe to use a random value as placeholder instead.
        'placeholder' => '{YOUR_API_KEY}',

        // Any extra authentication-related info for your users. Markdown and HTML are supported.
        'extra_info' => 'Also send `X-API-SECRET` in headers for decryption/encryption and merchant validation.',
    ],

    // Example requests for each endpoint will be shown in each of these languages.
    // Supported options are: bash, javascript, php, python
    // To add a language of your own, see https://scribe.knuckles.wtf/laravel/advanced/example-requests
    // Note: does not work for `external` docs types
    'example_languages' => [
        'bash',
        'javascript',
        'php',
        'python',
    ],

    // Generate a Postman collection (v2.1.0) in addition to HTML docs.
    // For 'static' docs, the collection will be generated to public/docs/collection.json.
    // For 'laravel' docs, it will be generated to storage/app/scribe/collection.json.
    // Setting `laravel.add_routes` to true (above) will also add a route for the collection.
    'postman' => [
        'enabled' => true,

        'overrides' => [
            // 'info.version' => '2.0.0',
        ],
    ],

    // Generate an OpenAPI spec (v3.0.1) in addition to docs webpage.
    // For 'static' docs, the collection will be generated to public/docs/openapi.yaml.
    // For 'laravel' docs, it will be generated to storage/app/scribe/openapi.yaml.
    // Setting `laravel.add_routes` to true (above) will also add a route for the spec.
    'openapi' => [
        'enabled' => true,

        'overrides' => [
            // 'info.version' => '2.0.0',
        ],

        // Additional generators to use when generating the OpenAPI spec.
        // Should extend `Knuckles\Scribe\Writing\OpenApiSpecGenerators\OpenApiGenerator`.
        'generators' => [],
    ],

    'groups' => [
        // Endpoints which don't have a @group will be placed in this default group.
        'default' => 'Endpoints',

        // By default, Scribe will sort groups alphabetically, and endpoints in the order their routes are defined.
        // You can override this by listing the groups, subgroups and endpoints here in the order you want them.
        // See https://scribe.knuckles.wtf/blog/laravel-v4#easier-sorting and https://scribe.knuckles.wtf/laravel/reference/config#order for details
        // Note: does not work for `external` docs types
        'order' => [],
    ],

    // Custom logo path. This will be used as the value of the src attribute for the <img> tag,
    // so make sure it points to an accessible URL or path. Set to false to not use a logo.
    // For example, if your logo is in public/img:
    // - 'logo' => '../img/logo.png' // for `static` type (output folder is public/docs)
    // - 'logo' => 'img/logo.png' // for `laravel` type
    'logo' => 'https://makemypayment.in/makemypayment-logo-white.svg',

    // Customize the "Last updated" value displayed in the docs by specifying tokens and formats.
    // Examples:
    // - {date:F j Y} => March 28, 2022
    // - {git:short} => Short hash of the last Git commit
    // Available tokens are `{date:<format>}` and `{git:<format>}`.
    // The format you pass to `date` will be passed to PHP's `date()` function.
    // The format you pass to `git` can be either "short" or "long".
    // Note: does not work for `external` docs types
    'last_updated' => 'Last updated: {date:F j, Y}',

    'examples' => [
        // Set this to any number to generate the same example values for parameters on each run,
        'faker_seed' => 1234,

        // With API resources and transformers, Scribe tries to generate example models to use in your API responses.
        // By default, Scribe will try the model's factory, and if that fails, try fetching the first from the database.
        // You can reorder or remove strategies here.
        'models_source' => ['factoryCreate', 'factoryMake', 'databaseFirst'],
    ],

    // The strategies Scribe will use to extract information about your routes at each stage.
    // Use configureStrategy() to specify settings for a strategy in the list.
    // Use removeStrategies() to remove an included strategy.
    'strategies' => [
        'metadata' => [
            ...Defaults::METADATA_STRATEGIES,
        ],
        'headers' => [
            ...Defaults::HEADERS_STRATEGIES,
            Strategies\StaticData::withSettings(data: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]),
        ],
        'urlParameters' => [
            ...Defaults::URL_PARAMETERS_STRATEGIES,
        ],
        'queryParameters' => [
            ...Defaults::QUERY_PARAMETERS_STRATEGIES,
        ],
        'bodyParameters' => [
            ...Defaults::BODY_PARAMETERS_STRATEGIES,
        ],
        'responses' => configureStrategy(
            Defaults::RESPONSES_STRATEGIES,
            Strategies\Responses\ResponseCalls::withSettings(
                only: ['GET *'],
                // Recommended: disable debug mode in response calls to avoid error stack traces in responses
                config: [
                    'app.debug' => false,
                ]
            )
        ),
        'responseFields' => [
            ...Defaults::RESPONSE_FIELDS_STRATEGIES,
        ]
    ],

    // For response calls, API resource responses and transformer responses,
    // Scribe will try to start database transactions, so no changes are persisted to your database.
    // Tell Scribe which connections should be transacted here. If you only use one db connection, you can leave this as is.
    'database_connections_to_transact' => [config('database.default')],

    'fractal' => [
        // If you are using a custom serializer with league/fractal, you can specify it here.
        'serializer' => null,
    ],
];
