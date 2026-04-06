{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest')

@section('title', 'Login - EMASPREMIUM')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-white mb-1">Selamat Datang Kembali</h2>
    <p class="text-sm text-gray-400">Silakan login ke akun EMASPREMIUM Anda</p>
</div>

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-300 mb-1">
            Email
        </label>
        <input id="email" 
               type="email" 
               name="email" 
               value="{{ old('email') }}" 
               required 
               autofocus
               class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition @error('email') border-red-500 @enderror"
               placeholder="nama@email.com">
        @error('email')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-300 mb-1">
            Password
        </label>
        <input id="password" 
               type="password" 
               name="password" 
               required
               class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition @error('password') border-red-500 @enderror"
               placeholder="Masukkan password Anda">
        @error('password')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Remember Me & Forgot Password -->
    <div class="flex items-center justify-between">
        <label class="flex items-center space-x-2">
            <input type="checkbox" name="remember" 
                   class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-gold focus:ring-gold focus:ring-offset-0">
            <span class="text-sm text-gray-300">Ingat saya</span>
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-gold hover:text-gold-dark transition">
                Lupa password?
            </a>
        @endif
    </div>

    <!-- Login Button -->
    <button type="submit" 
            class="w-full bg-gold text-white py-2.5 px-4 rounded-lg hover:bg-gold-dark transition font-semibold shadow-lg">
        Login
    </button>

    <!-- Divider -->
    <div class="relative my-4">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-700"></div>
        </div>
        <div class="relative flex justify-center text-xs">
            <span class="px-2 bg-gray-900 text-gray-400">Atau</span>
        </div>
    </div>

    <!-- Register Link -->
    <p class="text-center text-sm text-gray-400">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-gold hover:text-gold-dark transition font-medium">
            Daftar Sekarang
        </a>
    </p>
</form>
@endsection