<nav class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50 shadow-sm">
    <div class="container-custom">
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-xl md:text-3xl font-bold" style="color: var(--gold);">
                    EMAS<span class="text-black">PREMIUM</span>
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button type="button" id="mobile-menu-button" class="md:hidden p-2 rounded-lg text-gray-600 hover:text-gold focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <!-- Search Bar (Desktop) -->
                <div class="w-64 lg:w-80">
                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                        <input type="text" 
                               name="search" 
                               placeholder="Cari produk emas..." 
                               value="{{ request('search') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent text-sm">
                        <button type="submit" class="absolute right-3 top-2 text-gray-400 hover:text-gold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gold transition font-medium {{ request()->routeIs('products.*') ? 'text-gold' : '' }}">Produk</a>
                
                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-gold transition group">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    
                    @auth
                        @php $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity'); @endphp
                        @if($cartCount > 0)
                            <span class="cart-count-badge absolute -top-2 -right-2 bg-gold text-white text-xs rounded-full min-w-[20px] h-5 flex items-center justify-center px-1 shadow-md">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                        @endif
                    @endauth
                </a>
                
                @auth
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gold transition">
                            <div class="w-8 h-8 bg-gold-light rounded-full flex items-center justify-center">
                                <span class="text-gold font-semibold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden lg:inline-block font-medium">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border z-50">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gold-light">Dashboard Admin</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gold-light">Profil Saya</a>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gold-light">Pesanan Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gold transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-gold text-white px-4 py-2 rounded-lg hover:bg-gold-dark transition text-sm">Register</a>
                @endauth
            </div>
        </div>
        
        <!-- Mobile Menu (Hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden py-4 border-t border-gray-200">
            <!-- Search Bar (Mobile) -->
            <form action="{{ route('products.index') }}" method="GET" class="relative mb-4">
                <input type="text" 
                       name="search" 
                       placeholder="Cari produk emas..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent">
                <button type="submit" class="absolute right-3 top-2.5 text-gray-400 hover:text-gold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
            
            <!-- Mobile Navigation Links -->
            <div class="space-y-2">
                <a href="{{ route('products.index') }}" class="block py-2 text-gray-700 hover:text-gold transition {{ request()->routeIs('products.*') ? 'text-gold' : '' }}">Produk</a>
                
                <a href="{{ route('cart.index') }}" class="flex items-center justify-between py-2 text-gray-700 hover:text-gold transition">
                    <span>Keranjang</span>
                    @auth
                        @php $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity'); @endphp
                        @if($cartCount > 0)
                            <span class="bg-gold text-white text-xs rounded-full px-2 py-1">{{ $cartCount }}</span>
                        @endif
                    @endauth
                </a>
                
                @auth
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <p class="text-sm text-gray-500 mb-2">Akun: {{ Auth::user()->name }}</p>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block py-2 text-gray-700 hover:text-gold">Dashboard Admin</a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="block py-2 text-gray-700 hover:text-gold">Profil Saya</a>
                        <a href="{{ route('orders.index') }}" class="block py-2 text-gray-700 hover:text-gold">Pesanan Saya</a>
                        <form method="POST" action="{{ route('logout') }}" class="pt-2">
                            @csrf
                            <button type="submit" class="w-full text-left py-2 text-red-600 hover:text-red-800">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-gray-200 pt-2 mt-2 space-y-2">
                        <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-gold">Login</a>
                        <a href="{{ route('register') }}" class="block py-2 bg-gold text-white text-center px-4 rounded-lg hover:bg-gold-dark">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
// Toggle mobile menu
document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
});

// Close mobile menu on window resize
window.addEventListener('resize', function() {
    if (window.innerWidth >= 768) {
        document.getElementById('mobile-menu')?.classList.add('hidden');
    }
});
</script>