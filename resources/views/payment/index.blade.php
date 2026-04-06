{{-- resources/views/payment/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pembayaran - ' . $order->invoice_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold mb-2">Pembayaran</h1>
            <p class="text-gray-600">Invoice: <span class="font-semibold">{{ $order->invoice_number }}</span></p>
        </div>

        <div class="border-t border-b py-4 mb-4">
            <div class="flex justify-between mb-2">
                <span class="text-gray-600">Total Pembayaran</span>
                <span class="text-2xl font-bold text-gold">{{ $order->formatted_total }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Metode Pembayaran</span>
                <span class="font-semibold">
                    @if($order->payment_method == 'qris')
                        QRIS
                    @elseif($order->payment_method == 'transfer')
                        Transfer Bank
                    @elseif($order->payment_method == 'card')
                        Kartu Kredit/Debit
                    @endif
                </span>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="mb-6">
            <h2 class="font-semibold mb-3">Instruksi Pembayaran:</h2>
            
            @if($order->payment_method == 'qris')
                <div class="bg-gold-light p-4 rounded-lg text-center">
                    <div class="bg-white p-4 inline-block rounded-lg mx-auto mb-3">
                        <svg class="w-48 h-48" viewBox="0 0 200 200">
                            <rect x="20" y="20" width="160" height="160" fill="#f0f0f0" stroke="#333" stroke-width="2"/>
                            <rect x="40" y="40" width="30" height="30" fill="#333"/>
                            <rect x="80" y="40" width="30" height="30" fill="#333"/>
                            <rect x="130" y="40" width="30" height="30" fill="#333"/>
                            <rect x="40" y="80" width="30" height="30" fill="#333"/>
                            <rect x="130" y="80" width="30" height="30" fill="#333"/>
                            <rect x="40" y="130" width="30" height="30" fill="#333"/>
                            <rect x="80" y="130" width="30" height="30" fill="#333"/>
                            <rect x="130" y="130" width="30" height="30" fill="#333"/>
                        </svg>
                    </div>
                    <p class="text-sm">Scan QR code di atas menggunakan aplikasi e-wallet atau mobile banking</p>
                </div>
                
            @elseif($order->payment_method == 'transfer')
                <div class="space-y-3">
                    <div class="bg-gold-light p-4 rounded-lg">
                        <h3 class="font-semibold mb-2">Rekening Tujuan:</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between bg-white p-2 rounded">
                                <span>BCA</span>
                                <span class="font-mono">123 456 7890</span>
                            </div>
                            <div class="flex justify-between bg-white p-2 rounded">
                                <span>Mandiri</span>
                                <span class="font-mono">123-00-1234567-8</span>
                            </div>
                            <div class="flex justify-between bg-white p-2 rounded">
                                <span>BNI</span>
                                <span class="font-mono">0123456789</span>
                            </div>
                            <div class="flex justify-between bg-white p-2 rounded">
                                <span>BRI</span>
                                <span class="font-mono">1234-01-012345-67-8</span>
                            </div>
                        </div>
                        <p class="mt-3 text-sm">Atas nama: <span class="font-semibold">PT EMAS PREMIUM INDONESIA</span></p>
                    </div>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <p class="text-sm text-yellow-700">
                            <strong>Penting:</strong> Setelah transfer, konfirmasi pembayaran melalui WhatsApp 081234567890 dengan mengirimkan bukti transfer.
                        </p>
                    </div>
                </div>
                
            @elseif($order->payment_method == 'card')
                <div class="bg-gold-light p-4 rounded-lg">
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Nomor Kartu</label>
                        <input type="text" class="w-full border rounded-lg p-2" placeholder="1234 5678 9012 3456">
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Kadaluarsa</label>
                            <input type="text" class="w-full border rounded-lg p-2" placeholder="MM/YY">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">CVV</label>
                            <input type="text" class="w-full border rounded-lg p-2" placeholder="123">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Nama Pemilik Kartu</label>
                        <input type="text" class="w-full border rounded-lg p-2" placeholder="NAMA ANDA">
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-3">
            <form action="{{ route('payment.process', $order) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-gold text-white px-4 py-2 rounded-lg hover:bg-gold-dark transition">
                    Konfirmasi Pembayaran
                </button>
            </form>
            
            <a href="{{ route('orders.show', $order) }}" 
               class="flex-1 border border-gray-300 text-center px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection