{{-- resources/views/orders/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->invoice_number)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6 text-sm">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-gold">Home</a>
        <span class="mx-2 text-gray-400">/</span>
        <a href="{{ route('orders.index') }}" class="text-gray-500 hover:text-gold">Pesanan Saya</a>
        <span class="mx-2 text-gray-400">/</span>
        <span class="text-gold">Detail Pesanan</span>
    </nav>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Info Message -->
    @if(session('info'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded" role="alert">
            <p>{{ session('info') }}</p>
        </div>
    @endif

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl font-bold mb-2">Detail Pesanan</h1>
                <p class="text-gray-600">Invoice: <span class="font-semibold">{{ $order->invoice_number }}</span></p>
                <p class="text-gray-600">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status == 'diproses') bg-blue-100 text-blue-800
                    @elseif($order->status == 'selesai') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Order Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 text-gold mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Informasi Pengiriman
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Alamat Lengkap</p>
                        <p class="font-medium">{{ $order->address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nomor HP</p>
                        <p class="font-medium">{{ $order->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $order->email }}</p>
                    </div>
                    @if($order->notes)
                    <div>
                        <p class="text-sm text-gray-500">Catatan</p>
                        <p class="font-medium">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 text-gold mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Informasi Pembayaran
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Metode Pembayaran</p>
                        <p class="font-medium">
                            @if($order->payment_method == 'qris')
                                QRIS
                            @elseif($order->payment_method == 'transfer')
                                Transfer Bank
                            @elseif($order->payment_method == 'card')
                                Kartu Kredit/Debit
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status Pembayaran</p>
                        <p class="font-medium">
                            <span class="px-2 py-1 rounded-full text-xs
                                @if($order->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                @if($order->payment_status == 'paid')
                                    Lunas
                                @elseif($order->payment_status == 'pending')
                                    Menunggu Pembayaran
                                @else
                                    Gagal
                                @endif
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Payment Instructions (if pending) -->
                @if($order->status == 'pending' && $order->payment_status == 'pending')
                    <div class="mt-4 p-4 bg-gold-light rounded-lg" id="payment-instructions">
                        <h3 class="font-semibold mb-2">Instruksi Pembayaran:</h3>
                        
                        @if($order->payment_method == 'qris')
                            <div class="space-y-2">
                                <p>1. Buka aplikasi e-wallet atau mobile banking Anda</p>
                                <p>2. Pilih menu scan QRIS / QR Code</p>
                                <p>3. Scan kode QR berikut:</p>
                                <div class="bg-white p-4 inline-block rounded-lg mx-auto">
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
                                <p>4. Konfirmasi jumlah pembayaran: <span class="font-bold text-gold">{{ $order->formatted_total }}</span></p>
                                <p>5. Masukkan PIN untuk menyelesaikan pembayaran</p>
                            </div>
                        @elseif($order->payment_method == 'transfer')
                            <div class="space-y-2">
                                <p>Transfer ke salah satu rekening berikut:</p>
                                <div class="bg-white p-3 rounded">
                                    <p class="flex justify-between"><span>BCA</span> <span class="font-mono">123 456 7890</span></p>
                                    <p class="flex justify-between"><span>Mandiri</span> <span class="font-mono">123-00-1234567-8</span></p>
                                    <p class="flex justify-between"><span>BNI</span> <span class="font-mono">0123456789</span></p>
                                    <p class="flex justify-between"><span>BRI</span> <span class="font-mono">1234-01-012345-67-8</span></p>
                                </div>
                                <p class="mt-2">Atas nama: <span class="font-semibold">PT EMAS PREMIUM INDONESIA</span></p>
                                <p>Jumlah transfer: <span class="font-bold text-gold">{{ $order->formatted_total }}</span></p>
                                <p class="text-sm text-gray-600 mt-2">* Konfirmasi pembayaran melalui WhatsApp 081234567890</p>
                            </div>
                        @elseif($order->payment_method == 'card')
                            <div class="space-y-2">
                                <p>Anda akan diarahkan ke halaman pembayaran aman</p>
                                <p>Klik tombol "Bayar Sekarang" di bawah untuk melanjutkan</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Product List -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 text-gold mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Detail Produk
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Produk</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Harga</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Jumlah</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="font-medium">{{ $item->product_name }}</p>
                                        <p class="text-sm text-gray-500">SKU: {{ $item->product_sku }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $item->formatted_price }}</td>
                                <td class="px-4 py-3">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $item->formatted_subtotal }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-semibold">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right">Subtotal</td>
                                <td class="px-4 py-3">{{ $order->formatted_total }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right">Biaya Pengiriman</td>
                                <td class="px-4 py-3">Gratis</td>
                            </tr>
                            <tr class="text-gold">
                                <td colspan="3" class="px-4 py-3 text-right font-bold">Total</td>
                                <td class="px-4 py-3 font-bold">{{ $order->formatted_total }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column - Actions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h2 class="text-lg font-semibold mb-4">Aksi</h2>
                
                @if($order->status == 'pending')
                    <div class="space-y-3">
                        @if($order->payment_status == 'pending')
                            <button onclick="payNow({{ $order->id }}, '{{ $order->payment_method }}')" 
                                    class="w-full bg-gold text-white px-4 py-2 rounded-lg hover:bg-gold-dark transition flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Bayar Sekarang</span>
                            </button>
                            
                            @if($order->payment_method == 'transfer')
                                <button onclick="showTransferInstructions({{ $order->id }})" 
                                        class="w-full border border-gold text-gold px-4 py-2 rounded-lg hover:bg-gold-light transition flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Lihat Rekening</span>
                                </button>
                            @endif
                        @endif
                        
                        <button onclick="cancelOrder({{ $order->id }})" 
                                class="w-full border border-red-500 text-red-500 px-4 py-2 rounded-lg hover:bg-red-500 hover:text-white transition flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Batalkan Pesanan</span>
                        </button>
                    </div>
                @endif

                @if($order->payment_status == 'pending' && $order->status == 'pending')
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-yellow-700">
                                    Pesanan akan otomatis dibatalkan jika tidak dibayar dalam 24 jam.
                                </p>
                                <p class="text-xs text-yellow-600 mt-1">
                                    Batas waktu: {{ $order->created_at->addHours(24)->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-4 space-y-2 text-sm text-gray-600">
                    <p class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Pesanan dibuat: {{ $order->created_at->format('d M Y H:i') }}
                    </p>
                    
                    @if($order->paid_at)
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Dibayar: {{ $order->paid_at->format('d M Y H:i') }}
                        </p>
                    @endif
                    
                    @if($order->cancelled_at)
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Dibatalkan: {{ $order->cancelled_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </div>

                <!-- Download Invoice -->
                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('orders.invoice', $order) }}" 
                       class="flex items-center justify-center space-x-2 text-gray-600 hover:text-gold transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span>Download Invoice</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Konfigurasi URL
const paymentUrl = "{{ url('payment') }}";
const ordersUrl = "{{ url('orders') }}";

/**
 * Format waktu sisa
 */
function formatTimeRemaining(deadline) {
    const now = new Date().getTime();
    const deadlineTime = new Date(deadline).getTime();
    const timeRemaining = deadlineTime - now;
    
    if (timeRemaining <= 0) return 'Waktu habis';
    
    const hours = Math.floor(timeRemaining / (1000 * 60 * 60));
    const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
    
    return `${hours} jam ${minutes} menit`;
}

/**
 * Batalkan pesanan
 */
function cancelOrder(orderId) {
    Swal.fire({
        title: 'Batalkan Pesanan',
        text: 'Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Batalkan!',
        cancelButtonText: 'Tidak',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`${ordersUrl}/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Pesanan berhasil dibatalkan',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Gagal membatalkan pesanan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: error.message || 'Terjadi kesalahan, silakan coba lagi',
                    confirmButtonColor: '#D4AF37'
                });
            });
        }
    });
}

/**
 * Proses pembayaran
 */
function payNow(orderId, paymentMethod) {
    let message = 'Anda akan diarahkan ke halaman pembayaran.';
    
    if (paymentMethod === 'transfer') {
        message = 'Anda akan melihat instruksi transfer bank. Silakan transfer sesuai nominal.';
    } else if (paymentMethod === 'qris') {
        message = 'Anda akan melihat kode QRIS untuk scan.';
    } else if (paymentMethod === 'card') {
        message = 'Anda akan diarahkan ke halaman pembayaran kartu kredit/debit.';
    }
    
    Swal.fire({
        title: 'Proses Pembayaran',
        text: message,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#D4AF37',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `${paymentUrl}/${orderId}`;
        }
    });
}

/**
 * Tampilkan instruksi transfer
 */
function showTransferInstructions(orderId) {
    Swal.fire({
        title: 'Instruksi Transfer',
        html: `
            <div class="text-left">
                <p class="font-semibold mb-2">Transfer ke rekening:</p>
                <div class="bg-gray-50 p-3 rounded mb-3">
                    <p class="flex justify-between"><span>BCA</span> <span class="font-mono">123 456 7890</span></p>
                    <p class="flex justify-between"><span>Mandiri</span> <span class="font-mono">123-00-1234567-8</span></p>
                    <p class="flex justify-between"><span>BNI</span> <span class="font-mono">0123456789</span></p>
                    <p class="flex justify-between"><span>BRI</span> <span class="font-mono">1234-01-012345-67-8</span></p>
                </div>
                <p class="mb-2">Atas nama: <span class="font-semibold">PT EMAS PREMIUM INDONESIA</span></p>
                <p class="text-sm text-gray-600">Konfirmasi pembayaran melalui WhatsApp 081234567890</p>
            </div>
        `,
        confirmButtonColor: '#D4AF37',
        confirmButtonText: 'Saya Sudah Transfer',
        showCancelButton: true,
        cancelButtonText: 'Tutup'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `${paymentUrl}/${orderId}/instructions`;
        }
    });
}

/**
 * Cek status pembayaran (AJAX)
 */
function checkPaymentStatus(orderId) {
    fetch(`{{ url('api/payment') }}/${orderId}/check-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.paid) {
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Diterima!',
                text: 'Terima kasih, pembayaran Anda telah kami terima.',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    })
    .catch(error => console.error('Error checking payment:', error));
}

// Auto refresh status setiap 30 detik jika status masih pending
@if($order->status == 'pending' && $order->payment_status == 'pending')
    let checkInterval = setInterval(() => {
        checkPaymentStatus({{ $order->id }});
    }, 30000); // 30 detik

    // Hentikan interval saat page unload
    window.addEventListener('beforeunload', () => {
        clearInterval(checkInterval);
    });
@endif

// Tampilkan countdown timer untuk batas waktu pembayaran
@if($order->status == 'pending' && $order->payment_status == 'pending')
    const deadline = "{{ $order->created_at->addHours(24)->format('Y-m-d H:i:s') }}";
    
    function updateCountdown() {
        const timeRemaining = formatTimeRemaining(deadline);
        const countdownEl = document.getElementById('payment-countdown');
        if (countdownEl) {
            countdownEl.textContent = timeRemaining;
        }
    }
    
    setInterval(updateCountdown, 60000); // Update setiap menit
    updateCountdown(); // Panggil pertama kali
@endif

// Handle tombol copy rekening
document.querySelectorAll('.copy-account').forEach(button => {
    button.addEventListener('click', function() {
        const accountNumber = this.dataset.account;
        navigator.clipboard.writeText(accountNumber).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Tersalin!',
                text: 'Nomor rekening telah disalin',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    });
});
</script>
@endpush

@push('styles')
<style>
/* Sticky sidebar */
@media (min-width: 1024px) {
    .sticky {
        position: sticky;
        top: 6rem;
    }
}

/* Copy button hover */
.copy-account {
    cursor: pointer;
    transition: all 0.2s;
}

.copy-account:hover {
    background-color: #f3f4f6;
}

/* Payment instructions animation */
#payment-instructions {
    transition: all 0.3s ease;
}

/* Countdown timer */
#payment-countdown {
    font-weight: 600;
    color: #D4AF37;
}
</style>
@endpush