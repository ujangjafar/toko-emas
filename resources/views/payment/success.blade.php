{{-- resources/views/payment/success.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold mb-2">Pembayaran Berhasil!</h1>
        <p class="text-gray-600 mb-6">Terima kasih, pembayaran Anda telah kami terima.</p>
        
        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
            <div class="flex justify-between mb-2">
                <span class="text-gray-600">Invoice</span>
                <span class="font-semibold">{{ $order->invoice_number }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="text-gray-600">Total</span>
                <span class="font-bold text-gold">{{ $order->formatted_total }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Status</span>
                <span class="text-green-600 font-semibold">Lunas</span>
            </div>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('orders.show', $order) }}" 
               class="flex-1 bg-gold text-white px-4 py-2 rounded-lg hover:bg-gold-dark transition">
                Lihat Detail Pesanan
            </a>
            <a href="{{ route('products.index') }}" 
               class="flex-1 border border-gray-300 text-center px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                Belanja Lagi
            </a>
        </div>
    </div>
</div>
@endsection