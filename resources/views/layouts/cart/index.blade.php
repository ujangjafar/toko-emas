@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Keranjang <span style="color: var(--gold);">Belanja</span></h1>

    @if($cartItems->isEmpty())
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Produk</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Harga</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Jumlah</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Subtotal</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($cartItems as $item)
                                <tr class="cart-item" data-id="{{ $item->id }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($item->product->images->isNotEmpty())
                                                <img src="{{ Storage::url($item->product->images->first()->image_path) }}" 
                                                     alt="{{ $item->product->name }}"
                                                     class="w-16 h-16 object-cover rounded-lg mr-4">
                                            @endif
                                            <div>
                                                <h3 class="font-semibold">{{ $item->product->name }}</h3>
                                                <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="product-price" data-price="{{ $item->product->price }}">
                                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center border rounded-lg w-32">
                                            <button onclick="updateQuantity({{ $item->id }}, -1)" 
                                                    class="px-3 py-1 text-gray-600 hover:bg-gray-100">-</button>
                                            <input type="number" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->product->stock }}"
                                                   class="item-quantity w-12 text-center border-0 focus:ring-0"
                                                   data-id="{{ $item->id }}"
                                                   onchange="updateQuantityInput({{ $item->id }}, this.value)">
                                            <button onclick="updateQuantity({{ $item->id }}, 1)" 
                                                    class="px-3 py-1 text-gray-600 hover:bg-gray-100">+</button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="item-subtotal font-semibold text-gold">
                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button onclick="removeFromCart({{ $item->id }})" 
                                                class="text-red-500 hover:text-red-700">
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
            </div>

            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h2 class="text-xl font-semibold mb-4">Ringkasan Belanja</h2>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="cart-subtotal font-semibold">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Estimasi Pengiriman</span>
                            <span class="text-gray-800">Dihitung saat checkout</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span class="cart-total text-gold">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" 
                       class="block w-full bg-gold text-white text-center px-6 py-3 rounded-lg hover:bg-gold-dark transition font-semibold">
                        Lanjut ke Checkout
                    </a>
                    
                    <a href="{{ route('products.index') }}" 
                       class="block w-full text-center text-gray-600 mt-4 hover:text-gold transition">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function formatRupiah(amount) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
}

function updateQuantity(cartId, change) {
    let row = document.querySelector(`tr[data-id="${cartId}"]`);
    let input = row.querySelector('.item-quantity');
    let newValue = parseInt(input.value) + change;
    let max = parseInt(input.max);
    
    if (newValue >= 1 && newValue <= max) {
        input.value = newValue;
        updateCartItem(cartId, newValue);
    }
}

function updateQuantityInput(cartId, value) {
    let row = document.querySelector(`tr[data-id="${cartId}"]`);
    let input = row.querySelector('.item-quantity');
    let max = parseInt(input.max);
    
    value = parseInt(value);
    if (value >= 1 && value <= max) {
        updateCartItem(cartId, value);
    } else {
        input.value = input.defaultValue;
    }
}

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
            // Update subtotal for this item
            let row = document.querySelector(`tr[data-id="${cartId}"]`);
            let price = parseFloat(row.querySelector('.product-price').dataset.price);
            let subtotal = price * quantity;
            row.querySelector('.item-subtotal').textContent = formatRupiah(subtotal);
            
            // Update cart totals
            document.querySelector('.cart-subtotal').textContent = data.cart_total;
            document.querySelector('.cart-total').textContent = data.cart_total;
        }
    });
}

function removeFromCart(cartId) {
    Swal.fire({
        icon: 'warning',
        title: 'Hapus Produk',
        text: 'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
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
                    location.reload();
                }
            });
        }
    });
}
</script>
@endpush