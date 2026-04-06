<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <!-- Mobile Viewport - PENTING UNTUK RESPONSIF -->
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
        
        /* Mobile-first base styles */
        * {
            box-sizing: border-box;
        }
        
        html {
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
        }
        
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }
        
        /* Container responsif */
        .container-custom {
            width: 100%;
            padding-right: 1rem;
            padding-left: 1rem;
            margin-right: auto;
            margin-left: auto;
        }
        
        @media (min-width: 640px) {
            .container-custom {
                max-width: 640px;
                padding-right: 1.5rem;
                padding-left: 1.5rem;
            }
        }
        
        @media (min-width: 768px) {
            .container-custom {
                max-width: 768px;
            }
        }
        
        @media (min-width: 1024px) {
            .container-custom {
                max-width: 1024px;
                padding-right: 2rem;
                padding-left: 2rem;
            }
        }
        
        @media (min-width: 1280px) {
            .container-custom {
                max-width: 1280px;
            }
        }
        
        /* Utility classes untuk mobile */
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Aspect ratio */
        .aspect-square {
            aspect-ratio: 1 / 1;
        }
        
        .aspect-video {
            aspect-ratio: 16 / 9;
        }
        
        /* Touch-friendly */
        button, 
        a, 
        [role="button"] {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Hide scrollbar but keep functionality */
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 overflow-x-hidden">
    <!-- Navbar -->
    @include('layouts.navigation')
    
    <!-- Page Content -->
    <main class="min-h-screen pt-20 md:pt-24"> <!-- Padding top untuk navbar sticky -->
        <div class="container-custom">
            @yield('content')
        </div>
    </main>
    
    <!-- Footer -->
    @include('layouts.footer')
    
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
                position: 'top-end',
                showCloseButton: true
            });
        </script>
    @endif
    
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                confirmButtonColor: '#D4AF37',
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif
    
    @stack('scripts')
</body>
</html>