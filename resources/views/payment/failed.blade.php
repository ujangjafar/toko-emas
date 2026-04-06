{{-- resources/views/payment/failed.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran Gagal')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold mb-2">Pembayaran Gagal</h1>
        <p class="text-gray-600 mb-6">Maaf, terjadi kesalahan dalam proses pembayaran. Silakan coba lagi.</p>
        
        <div class="flex space-x-3">
            <a href="{{ route('payment.index', $order) }}" 
               class="flex-1 bg-gold text-white px-4 py-2 rounded-lg hover:bg-gold-dark transition">
                Coba Lagi
            </a>
            <a href="{{ route('orders.show', $order) }}" 
               class="flex-1 border border-gray-300 text-center px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection