{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.guest')

@section('title', 'Daftar - EMASPREMIUM')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-white mb-1">Buat Akun Baru</h2>
    <p class="text-sm text-gray-400">Daftar untuk mulai berbelanja perhiasan emas premium</p>
</div>

<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <!-- Nama Lengkap -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-300 mb-1">
            Nama Lengkap
        </label>
        <input id="name" 
               type="text" 
               name="name" 
               value="{{ old('name') }}" 
               required 
               autofocus
               class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition @error('name') border-red-500 @enderror"
               placeholder="Masukkan nama lengkap">
        @error('name')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @else
            <p class="mt-1 text-xs text-gray-500">Minimal 3 karakter</p>
        @enderror
    </div>

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
               placeholder="Minimal 8 karakter">
        
        <!-- Password Strength Meter -->
        <div class="mt-2">
            <div class="flex space-x-1 mb-1">
                <div class="h-1 w-1/4 bg-gray-700 rounded-full"></div>
                <div class="h-1 w-1/4 bg-gray-700 rounded-full"></div>
                <div class="h-1 w-1/4 bg-gray-700 rounded-full"></div>
                <div class="h-1 w-1/4 bg-gray-700 rounded-full"></div>
            </div>
            <p class="text-xs text-gray-500">Kekuatan password</p>
        </div>

        @error('password')
            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Konfirmasi Password -->
    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">
            Konfirmasi Password
        </label>
        <input id="password_confirmation" 
               type="password" 
               name="password_confirmation" 
               required
               class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold transition"
               placeholder="Ketik ulang password">
    </div>

    <!-- Terms & Conditions -->
    <div class="bg-gray-800/50 rounded-lg p-3">
        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input type="checkbox" name="terms" id="terms" required 
                       class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-gold focus:ring-gold focus:ring-offset-0">
            </div>
            <div class="ml-3">
                <label for="terms" class="text-xs text-gray-300">
                    Saya menyetujui 
                    <a href="#" class="text-gold hover:text-gold-dark transition">Syarat & Ketentuan</a>
                    dan
                    <a href="#" class="text-gold hover:text-gold-dark transition">Kebijakan Privasi</a>
                </label>
            </div>
        </div>
    </div>

    <!-- Register Button -->
    <button type="submit" 
            class="w-full bg-gold text-white py-2.5 px-4 rounded-lg hover:bg-gold-dark transition font-semibold shadow-lg">
        Daftar Sekarang
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

    <!-- Login Link -->
    <p class="text-center text-sm text-gray-400">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-gold hover:text-gold-dark transition font-medium">
            Login Sekarang
        </a>
    </p>
</form>
@endsection