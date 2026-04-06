@extends('layouts.app')

@section('title', 'Koleksi Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Koleksi <span style="color: var(--gold);">Produk</span></h1>
        <p class="text-gray-600">Temukan perhiasan emas impian Anda</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filter -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                <h2 class="font-semibold text-lg mb-4">Filter</h2>
                
                <!-- Categories -->
                <div class="mb-6">
                    <h3 class="font-medium mb-2">Kategori</h3>
                    <div class="space-y-2">
                        <a href="{{ route('products.index') }}" 
                           class="block {{ !request('category') ? 'text-gold font-semibold' : 'text-gray-600 hover:text-gold' }} transition">
                            Semua Produk
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', array_merge(request()->query(), ['category' => $category->id])) }}" 
                               class="block {{ request('category') == $category->id ? 'text-gold font-semibold' : 'text-gray-600 hover:text-gold' }} transition">
                                {{ $category->name }} ({{ $category->products_count }})
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <!-- Sort -->
                <div>
                    <h3 class="font-medium mb-2">Urutkan</h3>
                    <select onchange="window.location.href = this.value" 
                            class="w-full border-gray-300 rounded-lg focus:ring-gold focus:border-gold">
                        <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}"
                                {{ request('sort') == 'newest' ? 'selected' : '' }}>
                            Terbaru
                        </option>
                        <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}"
                                {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                            Harga: Rendah ke Tinggi
                        </option>
                        <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}"
                                {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                            Harga: Tinggi ke Rendah
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="lg:w-3/4">
            @if($products->isEmpty())
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <p class="text-gray-500 text-lg">Tidak ada produk yang ditemukan</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="group bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                            <div class="relative h-64 overflow-hidden">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif
                                @if($product->stock < 1)
                                    <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm">
                                        Habis
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-4">
                                <div class="text-sm text-gold mb-1">{{ $product->category->name }}</div>
                                <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                                <p class="text-gold font-bold text-xl mb-3">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="text-gray-600 hover:text-gold transition">
                                        Lihat Detail
                                    </a>
                                    @if($product->stock > 0)
                                        <button onclick="addToCart({{ $product->id }})"
                                                class="bg-gold text-white px-4 py-2 rounded-lg hover:bg-gold-dark transition text-sm">
                                            + Keranjang
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addToCart(productId) {
    fetch('{{ route("cart.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.requires_login) {
            Swal.fire({
                icon: 'info',
                title: 'Login Diperlukan',
                text: 'Silakan login terlebih dahulu untuk menambahkan produk ke keranjang',
                showCancelButton: true,
                confirmButtonText: 'Login',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("login") }}';
                }
            });
        } else if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            });
        } else if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.error
            });
        }
    });
}
</script>
@endpush