{{-- resources/views/checkout/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Checkout</h1>
        <p class="text-gray-600">Lengkapi data pengiriman dan pilih metode pembayaran</p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section (Left & Middle) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Shipping Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <span class="w-8 h-8 bg-gold-light text-gold rounded-full flex items-center justify-center mr-3">1</span>
                        Informasi Pengiriman
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" 
                                      id="address" 
                                      rows="3"
                                      class="w-full border-gray-300 rounded-lg focus:ring-gold focus:border-gold @error('address') border-red-500 @enderror"
                                      placeholder="Masukkan alamat lengkap (nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, provinsi)"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nomor HP <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone"
                                       value="{{ old('phone') }}"
                                       class="w-full border-gray-300 rounded-lg focus:ring-gold focus:border-gold @error('phone') border-red-500 @enderror"
                                       placeholder="081234567890"
                                       required>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email (Opsional)
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email"
                                       value="{{ old('email', Auth::user()->email) }}"
                                       class="w-full border-gray-300 rounded-lg focus:ring-gold focus:border-gold"
                                       placeholder="email@contoh.com">
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan untuk Penjual (Opsional)
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="2"
                                      class="w-full border-gray-300 rounded-lg focus:ring-gold focus:border-gold"
                                      placeholder="Contoh: Warna emas, ukuran, request khusus, dll">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 flex items-center">
                        <span class="w-8 h-8 bg-gold-light text-gold rounded-full flex items-center justify-center mr-3">2</span>
                        Metode Pembayaran
                    </h2>
                    
                    <div class="space-y-4">
                        <!-- QRIS -->
                        <div class="border rounded-lg p-4 hover:border-gold transition cursor-pointer payment-method" data-method="qris">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="qris" class="w-5 h-5 text-gold focus:ring-gold" checked>
                                <div class="ml-4 flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-700" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M5 3h4v4H5V3zm10 0h4v4h-4V3zM5 13h4v4H5v-4zm10 0h4v4h-4v-4zM3 5h2v2H3V5zm16 0h2v2h-2V5zM3 15h2v2H3v-2zm16 0h2v2h-2v-2zM5 7h2v2H5V7zm10 0h2v2h-2V7zM7 5h2v2H7V5zm8 0h2v2h-2V5zM5 17h2v2H5v-2zm10 0h2v2h-2v-2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">QRIS</h3>
                                        <p class="text-sm text-gray-500">Bayar dengan scan QR code menggunakan aplikasi e-wallet atau mobile banking</p>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- QRIS Preview (hidden by default, show when selected) -->
                            <div class="qris-preview hidden mt-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="bg-white p-4 rounded-lg inline-block mb-3">
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
                                        <p class="text-sm font-medium">Scan dengan aplikasi pembayaran</p>
                                        <p class="text-xs text-gray-500">Nomor: 081234567890 (EMAS PREMIUM)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transfer Bank -->
                        <div class="border rounded-lg p-4 hover:border-gold transition cursor-pointer payment-method" data-method="transfer">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="transfer" class="w-5 h-5 text-gold focus:ring-gold">
                                <div class="ml-4 flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">Transfer Bank</h3>
                                        <p class="text-sm text-gray-500">BCA, Mandiri, BNI, BRI, atau bank lainnya</p>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Bank Transfer Details (hidden by default, show when selected) -->
                            <div class="transfer-preview hidden mt-4 p-4 bg-gray-50 rounded-lg">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center border-b pb-2">
                                        <span class="font-medium">Bank BCA</span>
                                        <span class="text-gold font-mono">123 456 7890</span>
                                    </div>
                                    <div class="flex justify-between items-center border-b pb-2">
                                        <span class="font-medium">Bank Mandiri</span>
                                        <span class="text-gold font-mono">123-00-1234567-8</span>
                                    </div>
                                    <div class="flex justify-between items-center border-b pb-2">
                                        <span class="font-medium">Bank BNI</span>
                                        <span class="text-gold font-mono">0123456789</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">Bank BRI</span>
                                        <span class="text-gold font-mono">1234-01-012345-67-8</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Atas nama: PT EMAS PREMIUM INDONESIA</p>
                                </div>
                            </div>
                        </div>

                        <!-- Credit/Debit Card -->
                        <div class="border rounded-lg p-4 hover:border-gold transition cursor-pointer payment-method" data-method="card">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="card" class="w-5 h-5 text-gold focus:ring-gold">
                                <div class="ml-4 flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <div class="flex space-x-1">
                                            <svg class="w-8 h-8 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M22 4H2v16h20V4zm-2 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                                            </svg>
                                            <svg class="w-8 h-8 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                                                <circle cx="12" cy="12" r="10"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">Kartu Kredit/Debit</h3>
                                        <p class="text-sm text-gray-500">Visa, Mastercard, JCB, Amex</p>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Card Form (hidden by default, show when selected) -->
                            <div class="card-preview hidden mt-4 p-4 bg-gray-50 rounded-lg">
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kartu</label>
                                        <input type="text" 
                                               class="w-full border-gray-300 rounded-lg focus:ring-gold focus:border-gold" 
                                               placeholder="1234 5678 9012 3456"
                                               disabled>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                                            <input type="text" 
                                                   class="w-full border-gray-300 rounded-lg focus:ring-gold focus:border-gold" 
                                                   placeholder="MM/YY"
                                                   disabled>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                            <input type="text" 
                                                   class="w-full border-gray-300 rounded-lg focus:ring-gold focus:border-gold" 
                                                   placeholder="123"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Kartu</label>
                                        <input type="text" 
                                               class="w-full border-gray-300 rounded-lg focus:ring-gold focus:border-gold" 
                                               placeholder="NAMA ANDA"
                                               disabled>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Data akan diproses dengan aman</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary (Right) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>
                    
                    <!-- Product List -->
                    <div class="max-h-80 overflow-y-auto mb-4 space-y-3">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-3 py-2 border-b">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                    @if($item->product->images->isNotEmpty())
                                        <img src="{{ Storage::url($item->product->images->first()->image_path) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-sm">{{ $item->product->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gold text-sm">
                                        Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total Calculation -->
                    <div class="space-y-2 pt-4 border-t">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Biaya Pengiriman</span>
                            <span class="font-medium">Akan dihitung</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Biaya Layanan</span>
                            <span class="font-medium">Rp 0</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg pt-2 border-t mt-2">
                            <span>Total</span>
                            <span class="text-gold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-gold text-white text-center px-6 py-3 rounded-lg hover:bg-gold-dark transition font-semibold mt-6">
                        Buat Pesanan
                    </button>
                    
                    <p class="text-xs text-gray-500 text-center mt-4">
                        Dengan melanjutkan, Anda menyetujui 
                        <a href="#" class="text-gold hover:underline">Syarat & Ketentuan</a> dan 
                        <a href="#" class="text-gold hover:underline">Kebijakan Privasi</a> kami.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Toggle payment method details
document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function(e) {
        // Only trigger if clicking on the div, not the radio input
        if (e.target.tagName !== 'INPUT') {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            radio.dispatchEvent(event);
        }
    });
});

// Show/hide payment details based on selection
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Hide all previews
        document.querySelectorAll('.qris-preview, .transfer-preview, .card-preview').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Show selected preview
        if (this.value === 'qris') {
            document.querySelector('.qris-preview').classList.remove('hidden');
        } else if (this.value === 'transfer') {
            document.querySelector('.transfer-preview').classList.remove('hidden');
        } else if (this.value === 'card') {
            document.querySelector('.card-preview').classList.remove('hidden');
        }
    });
});

// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const address = document.getElementById('address').value.trim();
    const phone = document.getElementById('phone').value.trim();
    
    if (!address || !phone) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Harap lengkapi data pengiriman terlebih dahulu!',
            confirmButtonColor: '#D4AF37'
        });
        return;
    }
    
    // Show loading
    Swal.fire({
        title: 'Memproses Pesanan',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
});

// Auto-format phone number
document.getElementById('phone').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9+]/g, '');
});

// Auto-format address (prevent XSS)
document.getElementById('address').addEventListener('input', function(e) {
    // Sanitize input
    this.value = this.value.replace(/<[^>]*>/g, '');
});

// Initialize first payment method
document.addEventListener('DOMContentLoaded', function() {
    const firstRadio = document.querySelector('input[name="payment_method"]:checked');
    if (firstRadio) {
        const event = new Event('change', { bubbles: true });
        firstRadio.dispatchEvent(event);
    }
});
</script>
@endpush

@push('styles')
<style>
/* Sticky sidebar on desktop */
@media (min-width: 1024px) {
    .sticky {
        position: sticky;
        top: 6rem;
    }
}

/* Custom scrollbar */
.max-h-80::-webkit-scrollbar {
    width: 4px;
}

.max-h-80::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.max-h-80::-webkit-scrollbar-thumb {
    background: #D4AF37;
    border-radius: 10px;
}

.max-h-80::-webkit-scrollbar-thumb:hover {
    background: #b8960f;
}

/* Payment method hover */
.payment-method:hover {
    border-color: #D4AF37;
    background-color: #fef9e7;
}

/* Radio button styling */
input[type="radio"]:checked {
    background-color: #D4AF37;
    border-color: #D4AF37;
}
</style>
@endpush