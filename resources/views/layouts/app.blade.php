<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'yStore') - yStore</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
</head>
<body class="font-sans bg-background text-gray-800 antialiased">
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-accent tracking-tight">
                        <span class="text-primary">y</span>Store
                    </a>
                    <div class="hidden md:flex items-center gap-6">
                        <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-accent transition-colors duration-200">Beranda</a>
                        <a href="{{ route('products') }}" class="text-sm font-medium text-gray-600 hover:text-accent transition-colors duration-200">Produk</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @livewire('cart-badge')
                    <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-600 hover:text-accent transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
            </div>
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-100">
                <div class="flex flex-col gap-2 pt-3">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-accent transition-colors duration-200 px-2 py-1.5">Beranda</a>
                    <a href="{{ route('products') }}" class="text-sm font-medium text-gray-600 hover:text-accent transition-colors duration-200 px-2 py-1.5">Produk</a>
                    <a href="{{ route('cart') }}" class="text-sm font-medium text-gray-600 hover:text-accent transition-colors duration-200 px-2 py-1.5">Keranjang</a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-100 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold text-accent tracking-tight">
                        <span class="text-primary">y</span>Store
                    </h3>
                    <p class="mt-3 text-sm text-gray-500 leading-relaxed">
                        Toko pakaian pria terpercaya dengan koleksi fashion terbaru dan berkualitas. Belanja mudah, aman, dan nyaman.
                    </p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Menu</h4>
                    <ul class="mt-4 space-y-2">
                        <li><a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-accent transition-colors duration-200">Beranda</a></li>
                        <li><a href="{{ route('products') }}" class="text-sm text-gray-500 hover:text-accent transition-colors duration-200">Produk</a></li>
                        <li><a href="{{ route('cart') }}" class="text-sm text-gray-500 hover:text-accent transition-colors duration-200">Keranjang</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider">Kontak</h4>
                    <ul class="mt-4 space-y-2">
                        <li class="text-sm text-gray-500">WhatsApp: 0812-3456-7890</li>
                        <li class="text-sm text-gray-500">Email: hello@ystore.com</li>
                        <li class="text-sm text-gray-500">Senin - Sabtu, 09:00 - 21:00</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} yStore. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/6281234567890?text=Halo%20yStore%2C%20saya%20ingin%20bertanya!"
       target="_blank"
       rel="noopener noreferrer"
       class="fixed bottom-6 right-6 z-50 bg-green-500 hover:bg-green-600 text-white rounded-full p-3.5 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 animate-fade-in">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
        </svg>
    </a>

    @if(session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="fixed top-20 right-6 z-50 bg-accent text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    @livewireScripts
    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>
