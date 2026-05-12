<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Make My Payment API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
                    body .content .php-example code { display: none; }
                    body .content .python-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "https://api-uat.makemypayment.in";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.3.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.3.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;,&quot;php&quot;,&quot;python&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
            <img src="https://makemypayment.in/makemypayment-logo-white.svg" alt="logo" class="logo" style="padding-top: 10px;" width="100%"/>
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                            <button type="button" class="lang-button" data-language-name="php">php</button>
                                            <button type="button" class="lang-button" data-language-name="python">python</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-getting-started-with-your-api" class="tocify-header">
                <li class="tocify-item level-1" data-unique="getting-started-with-your-api">
                    <a href="#getting-started-with-your-api">Getting Started With Your API</a>
                </li>
                                    <ul id="tocify-subheader-getting-started-with-your-api" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="api-base-url">
                                <a href="#api-base-url">API Base URL</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="key-features">
                                <a href="#key-features">Key Features</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication">
                                <a href="#authentication">🔐 Authentication</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="encryption">
                                <a href="#encryption">🔒 Encryption</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="tip-for-developers">
                                <a href="#tip-for-developers">💡 Tip for Developers</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-payouts" class="tocify-header">
                <li class="tocify-item level-1" data-unique="payouts">
                    <a href="#payouts">Payouts</a>
                </li>
                                    <ul id="tocify-subheader-payouts" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="payouts-GETapi-api-v1-balance">
                                <a href="#payouts-GETapi-api-v1-balance">Get account balance.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payouts-GETapi-api-v1-payouts-initiate">
                                <a href="#payouts-GETapi-api-v1-payouts-initiate">Initiate a payout.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="payouts-GETapi-api-v1-payouts--transaction_id--status">
                                <a href="#payouts-GETapi-api-v1-payouts--transaction_id--status">Check payout status.</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: September 19, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>https://api-uat.makemypayment.in</code>
</aside>
<h1 id="getting-started-with-your-api">Getting Started With Your API</h1>
<p>This guide helps you get started with the Payment System API integration.</p>
<h2 id="api-base-url">API Base URL</h2>
<p>We provide separate base URLs for two environments:</p>
<ul>
<li>
<p><strong>UAT (Testing)</strong>: <a href="https://api-uat.makemypayment.in">https://api-uat.makemypayment.in</a><br />
Use this environment to test your API calls, integrations, and development workflow.</p>
</li>
<li>
<p><strong>Production</strong>: <a href="https://api.makemypayment.in">https://api.makemypayment.in</a><br />
Use this environment to interact with live data in production.</p>
</li>
</ul>
<blockquote>
<p><strong>Recommended Steps:</strong></p>
<ol>
<li>Start with the <strong>UAT</strong> base URL to explore the API and test requests.  </li>
<li>Once your integration works as expected, switch to the <strong>Production</strong> base URL.</li>
</ol>
</blockquote>
<h2 id="key-features">Key Features</h2>
<ul>
<li>✅ <strong>Initiate Payout</strong> – Initiate a payout for your account.</li>
<li>🔎 <strong>Check Payout Status</strong> – Track the status of any payout in real time.</li>
<li>💰 <strong>Retrieve Account Balances</strong> – Get your current available balance instantly.</li>
</ul>
<h2 id="authentication">🔐 Authentication</h2>
<p>All endpoints require <strong>authentication</strong> using two credentials:</p>
<ul>
<li><code>api_key</code> – Your unique API key.</li>
<li><code>api_secret</code> – Used with <strong>AES encryption</strong> for secure request signing.</li>
</ul>
<blockquote>
<p>Every request must include your credentials in the <strong>headers</strong> or <strong>request body</strong> as defined per endpoint.</p>
</blockquote>
<h2 id="encryption">🔒 Encryption</h2>
<p>All sensitive request data is encrypted using <strong>AES-256-CBC</strong>.  </p>
<ul>
<li><strong>Algorithm:</strong> AES-256-CBC  </li>
<li><strong>Default IV:</strong> <code>0g7H#8X2mTqjvLwR</code></li>
</ul>
<p>Ensure your client application implements the <strong>same encryption and decryption logic</strong> before sending the payload. The API expects the payload to be <strong>base64 encoded</strong> after encryption.</p>
<p>Example workflow:</p>
<ol>
<li>Prepare your request data as JSON.</li>
<li>Encrypt using AES-256-CBC with your <code>api_secret</code> and the default IV.</li>
<li>Base64 encode the encrypted string.</li>
<li>Send it in the request body or as defined by the endpoint.</li>
</ol>
<h2 id="tip-for-developers">💡 Tip for Developers</h2>
<p>Use the provided code examples (on the right in desktop view or below on mobile) to quickly test requests in your preferred programming language.<br />
Following the examples ensures correct headers, encryption, and payload structure.</p>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="payouts">Payouts</h1>

    <p>Get the status of a specific payout transaction by transaction ID.</p>

                                <h2 id="payouts-GETapi-api-v1-balance">Get account balance.</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-api-v1-balance">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api-uat.makemypayment.in/api/api/v1/balance" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api-uat.makemypayment.in/api/api/v1/balance"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://api-uat.makemypayment.in/api/api/v1/balance';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'https://api-uat.makemypayment.in/api/api/v1/balance'
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-api-v1-balance">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;balance&quot;: 15000.5
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-api-v1-balance" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-api-v1-balance"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-api-v1-balance"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-api-v1-balance" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-api-v1-balance">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-api-v1-balance" data-method="GET"
      data-path="api/api/v1/balance"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-api-v1-balance', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-api-v1-balance"
                    onclick="tryItOut('GETapi-api-v1-balance');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-api-v1-balance"
                    onclick="cancelTryOut('GETapi-api-v1-balance');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-api-v1-balance"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/api/v1/balance</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-api-v1-balance"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-api-v1-balance"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="payouts-GETapi-api-v1-payouts-initiate">Initiate a payout.</h2>

<p>
</p>

<p>Start a new payout transaction for the authenticated merchant.</p>
<p><strong>Note:</strong> The request body must be:</p>
<ul>
<li>AES encrypted (AES/CBC/PKCS5Padding)</li>
<li>Base64 encoded after encryption</li>
<li>Raw JSON payload before encryption should include: account_holder, account_number, bank_name, ifsc_code, amount, email, mobile</li>
</ul>

<span id="example-requests-GETapi-api-v1-payouts-initiate">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api-uat.makemypayment.in/api/api/v1/payouts/initiate" \
    --header "X-API-KEY: string required Your API key for authentication." \
    --header "X-API-SECRET: string required Your API secret for decryption and authentication." \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"account_holder\": \"John Doe\",
    \"account_number\": \"1234567890\",
    \"bank_name\": \"HDFC Bank\",
    \"ifsc_code\": \"HDFC0001234\",
    \"amount\": \"500\",
    \"email\": \"user@example.com\",
    \"mobile\": \"9876543210\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api-uat.makemypayment.in/api/api/v1/payouts/initiate"
);

const headers = {
    "X-API-KEY": "string required Your API key for authentication.",
    "X-API-SECRET": "string required Your API secret for decryption and authentication.",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "account_holder": "John Doe",
    "account_number": "1234567890",
    "bank_name": "HDFC Bank",
    "ifsc_code": "HDFC0001234",
    "amount": "500",
    "email": "user@example.com",
    "mobile": "9876543210"
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://api-uat.makemypayment.in/api/api/v1/payouts/initiate';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'X-API-KEY' =&gt; 'string required Your API key for authentication.',
            'X-API-SECRET' =&gt; 'string required Your API secret for decryption and authentication.',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'account_holder' =&gt; 'John Doe',
            'account_number' =&gt; '1234567890',
            'bank_name' =&gt; 'HDFC Bank',
            'ifsc_code' =&gt; 'HDFC0001234',
            'amount' =&gt; '500',
            'email' =&gt; 'user@example.com',
            'mobile' =&gt; '9876543210',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'https://api-uat.makemypayment.in/api/api/v1/payouts/initiate'
payload = {
    "account_holder": "John Doe",
    "account_number": "1234567890",
    "bank_name": "HDFC Bank",
    "ifsc_code": "HDFC0001234",
    "amount": "500",
    "email": "user@example.com",
    "mobile": "9876543210"
}
headers = {
  'X-API-KEY': 'string required Your API key for authentication.',
  'X-API-SECRET': 'string required Your API secret for decryption and authentication.',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-api-v1-payouts-initiate">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: true,
    &quot;message&quot;: &quot;Payout initiated successfully&quot;,
    &quot;data&quot;: &quot;TXN987654321&quot;,
    &quot;errors&quot;: null
}</code>
 </pre>
            <blockquote>
            <p>Example response (400):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: false,
    &quot;message&quot;: &quot;API key and secret are required&quot;,
    &quot;data&quot;: null,
    &quot;errors&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (400):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: false,
    &quot;message&quot;: &quot;Request body is not valid base64 encoded&quot;,
    &quot;data&quot;: null,
    &quot;errors&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (400):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: false,
    &quot;message&quot;: &quot;Failed to decrypt data&quot;,
    &quot;data&quot;: null,
    &quot;errors&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (400):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: false,
    &quot;message&quot;: &quot;Invalid JSON format after decryption&quot;,
    &quot;data&quot;: null,
    &quot;errors&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: false,
    &quot;message&quot;: &quot;Invalid API key or secret&quot;,
    &quot;data&quot;: null,
    &quot;errors&quot;: []
}</code>
 </pre>
            <blockquote>
            <p>Example response (422):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: false,
    &quot;message&quot;: &quot;Validation failed&quot;,
    &quot;data&quot;: null,
    &quot;errors&quot;: {
        &quot;account_number&quot;: [
            &quot;The account number field is required.&quot;
        ],
        &quot;amount&quot;: [
            &quot;The amount must be at least 100.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;status&quot;: false,
    &quot;message&quot;: &quot;Failed to initiate payout. Please try again.&quot;,
    &quot;data&quot;: null,
    &quot;errors&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-api-v1-payouts-initiate" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-api-v1-payouts-initiate"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-api-v1-payouts-initiate"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-api-v1-payouts-initiate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-api-v1-payouts-initiate">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-api-v1-payouts-initiate" data-method="GET"
      data-path="api/api/v1/payouts/initiate"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-api-v1-payouts-initiate', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-api-v1-payouts-initiate"
                    onclick="tryItOut('GETapi-api-v1-payouts-initiate');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-api-v1-payouts-initiate"
                    onclick="cancelTryOut('GETapi-api-v1-payouts-initiate');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-api-v1-payouts-initiate"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/api/v1/payouts/initiate</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-API-KEY</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-API-KEY"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="string required Your API key for authentication."
               data-component="header">
    <br>
<p>Example: <code>string required Your API key for authentication.</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>X-API-SECRET</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="X-API-SECRET"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="string required Your API secret for decryption and authentication."
               data-component="header">
    <br>
<p>Example: <code>string required Your API secret for decryption and authentication.</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>account_holder</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="account_holder"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="John Doe"
               data-component="body">
    <br>
<p>The full name of the bank account holder. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>account_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="account_number"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="1234567890"
               data-component="body">
    <br>
<p>The bank account number. Example: <code>1234567890</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>bank_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="bank_name"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="HDFC Bank"
               data-component="body">
    <br>
<p>The name of the bank. Example: <code>HDFC Bank</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>ifsc_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ifsc_code"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="HDFC0001234"
               data-component="body">
    <br>
<p>The IFSC code of the bank branch. Example: <code>HDFC0001234</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>amount</code></b>&nbsp;&nbsp;
<small>numeric</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="amount"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="500"
               data-component="body">
    <br>
<p>The payout amount (must be at least 100). Example: <code>500</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="user@example.com"
               data-component="body">
    <br>
<p>The email address of the beneficiary. Example: <code>user@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>mobile</code></b>&nbsp;&nbsp;
<small>numeric</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="mobile"                data-endpoint="GETapi-api-v1-payouts-initiate"
               value="9876543210"
               data-component="body">
    <br>
<p>The mobile number of the beneficiary (10 digits). Example: <code>9876543210</code></p>
        </div>
        </form>

                    <h2 id="payouts-GETapi-api-v1-payouts--transaction_id--status">Check payout status.</h2>

<p>
</p>



<span id="example-requests-GETapi-api-v1-payouts--transaction_id--status">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://api-uat.makemypayment.in/api/api/v1/payouts/consequatur/status" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://api-uat.makemypayment.in/api/api/v1/payouts/consequatur/status"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'https://api-uat.makemypayment.in/api/api/v1/payouts/consequatur/status';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'https://api-uat.makemypayment.in/api/api/v1/payouts/consequatur/status'
headers = {
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-api-v1-payouts--transaction_id--status">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;payout&quot;: {
        &quot;id&quot;: 1,
        &quot;reference&quot;: &quot;TXN123456&quot;,
        &quot;amount&quot;: &quot;1000.00&quot;,
        &quot;status&quot;: &quot;processing&quot;
    },
    &quot;status&quot;: &quot;processing&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Payout not found&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-api-v1-payouts--transaction_id--status" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-api-v1-payouts--transaction_id--status"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-api-v1-payouts--transaction_id--status"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-api-v1-payouts--transaction_id--status" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-api-v1-payouts--transaction_id--status">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-api-v1-payouts--transaction_id--status" data-method="GET"
      data-path="api/api/v1/payouts/{transaction_id}/status"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-api-v1-payouts--transaction_id--status', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-api-v1-payouts--transaction_id--status"
                    onclick="tryItOut('GETapi-api-v1-payouts--transaction_id--status');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-api-v1-payouts--transaction_id--status"
                    onclick="cancelTryOut('GETapi-api-v1-payouts--transaction_id--status');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-api-v1-payouts--transaction_id--status"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/api/v1/payouts/{transaction_id}/status</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-api-v1-payouts--transaction_id--status"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-api-v1-payouts--transaction_id--status"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>transaction_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="transaction_id"                data-endpoint="GETapi-api-v1-payouts--transaction_id--status"
               value="consequatur"
               data-component="url">
    <br>
<p>The ID of the payout transaction. Example: <code>consequatur</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                                        <button type="button" class="lang-button" data-language-name="php">php</button>
                                                        <button type="button" class="lang-button" data-language-name="python">python</button>
                            </div>
            </div>
</div>
</body>
</html>
