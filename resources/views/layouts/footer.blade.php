<footer class="bg-gray-900 text-white mt-12 md:mt-16">
    <div class="container-custom py-8 md:py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <!-- Company Info -->
            <div class="text-center sm:text-left">
                <h3 class="text-xl md:text-2xl font-bold mb-4" style="color: var(--gold);">EMASPREMIUM</h3>
                <p class="text-sm md:text-base text-gray-400">Toko emas terpercaya sejak 2024. Menyediakan berbagai perhiasan emas berkualitas tinggi.</p>
            </div>
            
            <!-- Quick Links -->
            <div class="text-center sm:text-left">
                <h4 class="text-base md:text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm md:text-base">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-gold transition">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gold transition">Produk</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-gold transition">Tentang Kami</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-gold transition">Kontak</a></li>
                </ul>
            </div>
            
            <!-- Categories -->
            <div class="text-center sm:text-left">
                <h4 class="text-base md:text-lg font-semibold mb-4">Kategori</h4>
                <ul class="space-y-2 text-sm md:text-base">
                    @php
                        $footerCategories = \App\Models\Category::limit(4)->get();
                    @endphp
                    @foreach($footerCategories as $category)
                        <li>
                            <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                               class="text-gray-400 hover:text-gold transition">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            <!-- Contact -->
            <div class="text-center sm:text-left">
                <h4 class="text-base md:text-lg font-semibold mb-4">Kontak</h4>
                <ul class="space-y-2 text-sm md:text-base text-gray-400">
                    <li class="flex items-center justify-center sm:justify-start space-x-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="break-all">+62 123 4567 890</span>
                    </li>
                    <li class="flex items-center justify-center sm:justify-start space-x-2">
                        <svg class="w-4 h-4 md:w-5 md:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="break-all">info@emaspremium.com</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-800 mt-8 pt-6 md:pt-8 text-center text-sm md:text-base text-gray-400">
            <p>&copy; {{ date('Y') }} EMASPREMIUM. All rights reserved.</p>
        </div>
    </div>
</footer>