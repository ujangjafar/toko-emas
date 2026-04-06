<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Toko Emas Premium') - EMASPREMIUM</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --gold: #D4AF37;
            --gold-light: #f5e7c8;
            --gold-dark: #b8960f;
        }
        
        .bg-gold-gradient {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        }
        
        /* Mobile-first adjustments */
        html {
            font-size: 14px;
        }
        
        @media (min-width: 640px) {
            html {
                font-size: 16px;
            }
        }
        
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        
        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 2px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gold-gradient min-h-screen flex flex-col">
    <!-- Header -->
    <header class="py-3 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="text-lg sm:text-xl font-bold">
                    <span class="text-gold">EMAS</span><span class="text-white">PREMIUM</span>
                </a>
                
                <div class="hidden sm:flex items-center space-x-4">
                    <span class="text-xs sm:text-sm text-gray-400">Toko Emas Terpercaya Sejak 2024</span>
                </div>
                
                <div class="sm:hidden">
                    <span class="text-xs text-gray-400">Sejak 2024</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-gray-900/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/10 overflow-hidden">
                <div class="p-5 sm:p-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                <div class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} EMASPREMIUM. All rights reserved.
                </div>
                <div class="flex space-x-4 text-xs">
                    <a href="#" class="text-gray-500 hover:text-gold transition">Kebijakan Privasi</a>
                    <a href="#" class="text-gray-500 hover:text-gold transition">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Session Messages -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                background: '#1a1a1a',
                color: '#fff',
                confirmButtonColor: '#D4AF37',
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>