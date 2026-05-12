<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $metaTitle ?? config('app.name', 'MakeMyPayment') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fonts & Tailwind (if not already included via Vite) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; }
        .right-bg {
            background: #1563ff;
            background-image: linear-gradient(135deg, #1563ff 0%, #1e40af 100%);
            position: relative;
        }
        .right-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: linear-gradient(135deg, #1563ff 0%, #1e40af 100%);
            opacity: 0.95;
            z-index: 0;
        }
        .grid-lines {
            background-image: linear-gradient(to right, rgba(255,255,255,0.07) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc] flex items-stretch">
    <div class="flex flex-1 min-h-screen">
        <!-- Left: Login Form -->
        <div class="w-full md:w-1/2 flex flex-col justify-center px-8 py-12 bg-white">
            {{ $slot }}
        </div>
        <!-- Right: Illustration/Info -->
        <div class="hidden md:flex w-1/2 right-bg grid-lines items-center justify-center relative">
            <div class="relative z-10 w-full flex flex-col items-center justify-center px-12 py-16">
                <div class="uppercase tracking-widest text-white/80 text-xs mb-6">Global Reach</div>
                <h2 class="text-3xl font-bold text-white mb-4 leading-tight">Want to sell to customers<br>anywhere in the world?</h2>
                <p class="text-white/80 text-base mb-8 max-w-md">
                    A good payment gateway makes it easy! With support for multiple currencies and payment methods, your business can reach international buyers without the headaches.<br><br>
                    It’s like having a passport for payments, letting you grow without borders.
                </p>
            </div>
            <div class="absolute bottom-0">
                <div class="w-full flex justify-center">
                    <!-- SVG Globe Illustration -->
                    <svg width="320" height="160" viewBox="0 0 320 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="160" cy="160" rx="160" ry="160" fill="#fff" fill-opacity="0.08"/>
                        <ellipse cx="160" cy="160" rx="140" ry="140" fill="#fff" fill-opacity="0.12"/>
                        <path d="M80 140 Q120 120 160 140 T240 140" stroke="#fff" stroke-width="2" fill="none"/>
                        <path d="M110 120 Q160 100 210 120" stroke="#fff" stroke-width="2" fill="none"/>
                        <path d="M140 100 Q160 90 180 100" stroke="#fff" stroke-width="2" fill="none"/>
                        <path d="M160 160 Q170 120 200 110 Q210 120 220 140" stroke="#fff" stroke-width="2" fill="none"/>
                        <path d="M160 160 Q150 120 120 110 Q110 120 100 140" stroke="#fff" stroke-width="2" fill="none"/>
                        <circle cx="160" cy="160" r="120" fill="#fff" fill-opacity="0.04"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
