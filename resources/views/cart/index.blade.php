{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Keranjang <span class="text-gold">Belanja</span></h1>
        <p class="text-gray-600">Review dan update item sebelum checkout</p>
    </div>

    @if($cartItems->isEmpty())
        <!-- Empty Cart -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-gray-500 text-lg mb-4">Keranjang belanja Anda kosong</p>
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-gold text-white px-6 py-3 rounded-lg hover:bg-gold-dark transition">
                Mulai Belanja
            </a>
        </div>
    @else
        <!-- Cart Items -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Produk</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Harga</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Jumlah</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Subtotal</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                            <tr class="cart-item" data-id="{{ $item->id }}" data-price="{{ $item->product->price }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
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
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                                            @if($item->product->stock < 5)
                                                <p class="text-xs text-orange-600 mt-1">Stok tersisa {{ $item->product->stock }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="product-price font-medium text-gray-900">
                                        Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center border rounded-lg w-32">
                                        <button type="button" 
                                                onclick="updateQuantity({{ $item->id }}, -1)"
                                                class="quantity-decrease px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-l-lg transition"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <input type="number" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               max="{{ $item->product->stock }}"
                                               data-id="{{ $item->id }}"
                                               class="item-quantity w-12 text-center border-0 focus:ring-0 text-sm font-medium"
                                               onchange="updateQuantityInput({{ $item->id }}, this.value)">
                                        <button type="button" 
                                                onclick="updateQuantity({{ $item->id }}, 1)"
                                                class="quantity-increase px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-r-lg transition"
                                                {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="item-subtotal font-semibold text-gold">
                                        Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="removeFromCart({{ $item->id }})" 
                                            class="text-red-500 hover:text-red-700 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center mt-6">
                    <a href="{{ route('products.index') }}" 
                       class="flex items-center space-x-2 text-gray-600 hover:text-gold transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Lanjut Belanja</span>
                    </a>
                    <button onclick="clearCart()" 
                            class="text-red-500 hover:text-red-700 transition text-sm">
                        Kosongkan Keranjang
                    </button>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-xl font-semibold mb-4">Ringkasan Belanja</h2>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Total Item</span>
                            <span class="total-items font-semibold">{{ $totalItems }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="cart-subtotal font-semibold">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Estimasi Pengiriman</span>
                            <span>Dihitung saat checkout</span>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span class="cart-total text-gold">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" 
                       class="block w-full bg-gold text-white text-center px-6 py-3 rounded-lg hover:bg-gold-dark transition font-semibold mb-3">
                        Lanjut ke Checkout
                    </a>
                    
                    @if($totalItems > 0)
                        <p class="text-xs text-gray-500 text-center">
                            Dengan melanjutkan, Anda menyetujui 
                            <a href="#" class="text-gold hover:underline">Syarat & Ketentuan</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Format Rupiah
function formatRupiah(amount) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
}

// Update quantity (+/-)
function updateQuantity(cartId, change) {
    const row = document.querySelector(`tr[data-id="${cartId}"]`);
    const input = row.querySelector('.item-quantity');
    const currentValue = parseInt(input.value);
    const max = parseInt(input.max);
    const newValue = currentValue + change;
    
    if (newValue >= 1 && newValue <= max) {
        input.value = newValue;
        updateCartItem(cartId, newValue);
    }
}

// Update quantity dari input langsung
function updateQuantityInput(cartId, value) {
    const row = document.querySelector(`tr[data-id="${cartId}"]`);
    const input = row.querySelector('.item-quantity');
    const max = parseInt(input.max);
    const min = parseInt(input.min);
    
    value = parseInt(value);
    if (value >= min && value <= max) {
        updateCartItem(cartId, value);
    } else {
        input.value = input.defaultValue;
        Swal.fire({
            icon: 'error',
            title: 'Jumlah tidak valid',
            text: `Maksimal pembelian ${max} item`,
            timer: 2000,
            showConfirmButton: false
        });
    }
}

// Update cart item via AJAX
function updateCartItem(cartId, quantity) {
    fetch(`{{ url('cart') }}/${cartId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update subtotal item
            const row = document.querySelector(`tr[data-id="${cartId}"]`);
            const price = parseFloat(row.dataset.price);
            const subtotal = price * quantity;
            row.querySelector('.item-subtotal').textContent = formatRupiah(subtotal);
            
            // Update cart totals
            document.querySelector('.cart-subtotal').textContent = data.cart_total_formatted;
            document.querySelector('.cart-total').textContent = data.cart_total_formatted;
            
            // Update total items
            const totalItems = document.querySelector('.total-items');
            if (totalItems) {
                const currentTotal = parseInt(totalItems.textContent);
                totalItems.textContent = currentTotal + (quantity - parseInt(row.querySelector('.item-quantity').defaultValue));
            }
            
            // Update cart badge
            if (typeof updateCartCount === 'function') {
                updateCartCount(data.cart_count);
            }
            
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Keranjang berhasil diperbarui',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.error
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan, silakan coba lagi'
        });
    });
}

// Remove item from cart
function removeFromCart(cartId) {
    Swal.fire({
        title: 'Hapus Produk',
        text: 'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ url('cart') }}/${cartId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hapus row dari table
                    const row = document.querySelector(`tr[data-id="${cartId}"]`);
                    row.remove();
                    
                    // Update cart badge
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(data.cart_count);
                    }
                    
                    // Update total items
                    const totalItems = document.querySelector('.total-items');
                    if (totalItems) {
                        totalItems.textContent = data.cart_count;
                    }
                    
                    // Update cart totals
                    if (data.cart_total_formatted) {
                        document.querySelector('.cart-subtotal').textContent = data.cart_total_formatted;
                        document.querySelector('.cart-total').textContent = data.cart_total_formatted;
                    }
                    
                    // Cek apakah cart masih ada item
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        location.reload(); // Reload untuk menampilkan empty state
                    }
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: 'Produk telah dihapus dari keranjang',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
}

// Clear entire cart
function clearCart() {
    if (document.querySelectorAll('.cart-item').length === 0) return;
    
    Swal.fire({
        title: 'Kosongkan Keranjang',
        text: 'Apakah Anda yakin ingin mengosongkan semua item di keranjang?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Kosongkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("cart.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart badge
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(0);
                    }
                    
                    // Reload halaman
                    location.reload();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Keranjang telah dikosongkan',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
}

// Disable buttons based on quantity
document.querySelectorAll('.cart-item').forEach(row => {
    const input = row.querySelector('.item-quantity');
    const decreaseBtn = row.querySelector('.quantity-decrease');
    const increaseBtn = row.querySelector('.quantity-increase');
    
    input.addEventListener('change', function() {
        const value = parseInt(this.value);
        const max = parseInt(this.max);
        
        decreaseBtn.disabled = value <= 1;
        increaseBtn.disabled = value >= max;
    });
});

// Refresh cart count on page load
document.addEventListener('DOMContentLoaded', function() {
    if (typeof refreshCartCount === 'function') {
        refreshCartCount();
    }
});
</script>
@endpush

@push('styles')
<style>
/* Quantity input styling */
.item-quantity::-webkit-inner-spin-button,
.item-quantity::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.item-quantity {
    -moz-appearance: textfield;
}

/* Disabled button styling */
.quantity-decrease:disabled,
.quantity-increase:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Hover effects */
.cart-item:hover {
    background-color: #f9fafb;
}

/* Sticky summary on mobile */
@media (max-width: 1024px) {
    .sticky {
        position: relative;
        top: 0;
    }
}
</style>
@endpush