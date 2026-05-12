<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle ?? 'Make My Payment - Secure Online Fintech Payment Solutions' }}</title>

    {{-- SEO Meta Tags --}}
    <meta name="description" content="{{ $metaDescription ?? 'Make My Payment offers secure, fast, and reliable fintech payment solutions for businesses and individuals. Experience seamless online transactions, bill payments, and financial services with advanced security and support.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'fintech, online payment, secure payment, bill payment, digital wallet, payment gateway, Make My Payment, financial services, online transactions' }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="{{ $metaTitle ?? 'Make My Payment - Secure Online Fintech Payment Solutions' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Make My Payment offers secure, fast, and reliable fintech payment solutions for businesses and individuals. Experience seamless online transactions, bill payments, and financial services with advanced security and support.' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle ?? 'Make My Payment - Secure Online Fintech Payment Solutions' }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'Make My Payment offers secure, fast, and reliable fintech payment solutions for businesses and individuals. Experience seamless online transactions, bill payments, and financial services with advanced security and support.' }}">
    <meta name="twitter:image" content="{{ asset('images/og-image.png') }}">

    @vite('resources/css/app.css')

    @livewireStyles
    @stack('styles')
</head>
<body class="bg-slate-950 text-slate-100 antialiased">
    <x-site.header />
    {{ $slot }}
    <x-site.footer />

    @vite('resources/js/app.js')
    @livewireScripts
    @stack('scripts')
</body>
</html>