<?php
use Livewire\Component;
use App\Models\Category;
use App\Models\Product;

new class extends Component {
    public function render()
    {
        return view('pages.⚡home', [
            'categories' => Category::withCount('products')->get(),
            'bestSellers' => Product::with('category')
                ->where('is_best_seller', true)
                ->take(4)
                ->get(),
            'newArrivals' => Product::with('category')
                ->where('is_new_arrival', true)
                ->take(4)
                ->get(),
        ]);
    }
};
?>

<div class="animate-fade-in">
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-accent via-secondary to-primary overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="animate-fade-up stagger-1">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight">
                        Tampil <span class="text-accent">Stylish</span><br>
                        Setiap Hari
                    </h1>
                    <p class="mt-4 text-lg text-white/80 max-w-md">
                        Koleksi fashion pria terbaru dengan kualitas terbaik. Dari casual hingga formal, semua ada di yStore.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('products') }}"
                           class="inline-flex items-center gap-2 bg-white text-accent font-semibold px-8 py-3 rounded-full hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            Belanja Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                        <a href="#best-seller"
                           class="inline-flex items-center gap-2 border-2 border-white/40 text-white font-semibold px-8 py-3 rounded-full hover:bg-white/10 transition-all duration-300">
                            Lihat Koleksi
                        </a>
                    </div>
                </div>
                <div class="hidden lg:flex justify-center animate-fade-up stagger-3">
                    <div class="relative">
                        <div class="w-72 h-72 bg-white/10 rounded-full absolute -top-6 -right-6 blur-xl"></div>
                        <div class="relative bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white/10 rounded-xl p-4 text-center">
                                    <div class="text-3xl font-bold text-white">+500</div>
                                    <div class="text-sm text-white/70 mt-1">Produk</div>
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 text-center">
                                    <div class="text-3xl font-bold text-white">+1K</div>
                                    <div class="text-sm text-white/70 mt-1">Pelanggan</div>
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 text-center">
                                    <div class="text-3xl font-bold text-white">100%</div>
                                    <div class="text-sm text-white/70 mt-1">Original</div>
                                </div>
                                <div class="bg-white/10 rounded-xl p-4 text-center">
                                    <div class="text-3xl font-bold text-white">Gratis</div>
                                    <div class="text-sm text-white/70 mt-1">Ongkir*</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Best Seller Section --}}
    <section id="best-seller" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Best Seller</h2>
                <p class="mt-1 text-sm text-gray-500">Produk paling laris di yStore</p>
            </div>
            <a href="{{ route('products') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-accent hover:text-secondary transition-colors duration-200">
                Lihat Semua
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 3 3m0 0 3-3m-3 3v-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($bestSellers as $index => $product)
                <a href="{{ route('product.detail', $product->slug) }}"
                   class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-up stagger-{{ min($index + 1, 6) }}">
                    <div class="aspect-[4/5] overflow-hidden bg-gray-100">
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $product->category->name }}</p>
                        <h3 class="mt-1 text-sm font-semibold text-gray-900 group-hover:text-accent transition-colors duration-200 line-clamp-1">{{ $product->name }}</h3>
                        <p class="mt-2 text-base font-bold text-accent">{{ $product->formatted_price }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Categories Section --}}
    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Kategori</h2>
                <p class="mt-1 text-sm text-gray-500">Temukan gaya favoritmu</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('products', ['category' => $category->slug]) }}"
                       class="group bg-background rounded-xl p-6 text-center hover:bg-primary/20 transition-all duration-300 hover:-translate-y-1">
                        <div class="size-14 mx-auto rounded-full bg-white flex items-center justify-center shadow-sm group-hover:shadow-md transition-all duration-300">
                            @switch($category->slug)
                                @case('kemeja-t-shirt')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-accent">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                    @break
                                @case('jaket-hoodie')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-accent">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                    @break
                                @case('celana')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-accent">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    @break
                                @case('sepatu')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-accent">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                    @break
                                @case('aksesoris')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-accent">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                    </svg>
                                    @break
                            @endswitch
                        </div>
                        <h3 class="mt-3 text-sm font-semibold text-gray-900">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $category->products_count }} produk</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Promo Banner --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="relative bg-gradient-to-r from-accent via-secondary to-primary rounded-2xl overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
            </div>
            <div class="relative px-6 sm:px-12 py-10 sm:py-14 text-center text-white">
                <div class="inline-flex items-center gap-2 bg-white/20 rounded-full px-4 py-1.5 text-sm font-medium mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                    Promo Spesial
                </div>
                <h3 class="text-2xl sm:text-3xl font-bold">Gratis Ongkir!</h3>
                <p class="mt-2 text-white/80 max-w-lg mx-auto">
                    Belanja minimal Rp200.000 dan nikmati gratis ongkir ke seluruh Indonesia. Periode terbatas, jangan lewatkan!
                </p>
                <a href="{{ route('products') }}"
                   class="inline-flex items-center gap-2 mt-6 bg-white text-accent font-semibold px-8 py-3 rounded-full hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl">
                    Belanja Sekarang
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- New Arrival Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">New Arrival</h2>
                <p class="mt-1 text-sm text-gray-500">Koleksi terbaru yStore</p>
            </div>
            <a href="{{ route('products') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-accent hover:text-secondary transition-colors duration-200">
                Lihat Semua
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 3 3m0 0 3-3m-3 3v-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($newArrivals as $index => $product)
                <a href="{{ route('product.detail', $product->slug) }}"
                   class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-up stagger-{{ min($index + 1, 6) }}">
                    <div class="relative aspect-[4/5] overflow-hidden bg-gray-100">
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-secondary text-white text-xs font-bold px-3 py-1 rounded-full">Baru</span>
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $product->category->name }}</p>
                        <h3 class="mt-1 text-sm font-semibold text-gray-900 group-hover:text-accent transition-colors duration-200 line-clamp-1">{{ $product->name }}</h3>
                        <p class="mt-2 text-base font-bold text-accent">{{ $product->formatted_price }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</div>
