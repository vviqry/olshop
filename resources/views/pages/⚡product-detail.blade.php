<?php
use Livewire\Component;
use App\Models\Product;

new class extends Component {
    public Product $product;

    public function addToCart(): void
    {
        $cart = session('cart', []);
        $cart[$this->product->id] = ($cart[$this->product->id] ?? 0) + 1;
        session(['cart' => $cart]);

        $this->dispatch('cart-updated', count: count($cart));
        session()->flash('message', 'Produk ditambahkan ke keranjang!');
    }

    public function mount(Product $product): void
    {
        $this->product = $product->load('category');
    }

    public function render()
    {
        return view('pages.⚡product-detail', [
            'relatedProducts' => Product::with('category')
                ->where('category_id', $this->product->category_id)
                ->where('id', '!=', $this->product->id)
                ->take(4)
                ->get(),
        ]);
    }
};
?>

<div class="animate-fade-in">
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-accent transition-colors duration-200">Beranda</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 5.25 6 6-6 6" />
            </svg>
            <a href="{{ route('products') }}" class="hover:text-accent transition-colors duration-200">Produk</a>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="m9 5.25 6 6-6 6" />
            </svg>
            <span class="text-gray-600">{{ $product->name }}</span>
        </nav>

        {{-- Product Detail --}}
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12">
            {{-- Image --}}
            <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-gray-100 shadow-sm">
                <img src="{{ $product->image_url }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            </div>

            {{-- Info --}}
            <div class="flex flex-col">
                <p class="text-sm text-gray-400 uppercase tracking-wider">{{ $product->category->name }}</p>
                <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

                <div class="flex items-center gap-3 mt-4">
                    @if($product->is_best_seller)
                        <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-3 py-1 rounded-full">Best Seller</span>
                    @endif
                    @if($product->is_new_arrival)
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">New Arrival</span>
                    @endif
                </div>

                <p class="mt-6 text-3xl font-bold text-accent">{{ $product->formatted_price }}</p>

                <p class="mt-6 text-gray-600 leading-relaxed">{{ $product->description }}</p>

                <div class="mt-8 space-y-3">
                    <button wire:click="addToCart"
                            class="w-full bg-accent hover:bg-secondary text-white font-semibold py-3.5 rounded-xl transition-all duration-200 hover:shadow-lg active:scale-[0.98]">
                        Tambah ke Keranjang
                    </button>
                    <a href="https://wa.me/6281234567890?text=Halo%20yStore%2C%20saya%20ingin%20bertanya%20tentang%20{{ urlencode($product->name) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="flex items-center justify-center gap-2 w-full border-2 border-green-500 text-green-600 font-semibold py-3.5 rounded-xl hover:bg-green-50 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                        </svg>
                        Tanya via WhatsApp
                    </a>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if($relatedProducts->isNotEmpty())
            <section class="mt-16">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6">Produk Terkait</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('product.detail', $related->slug) }}"
                           class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                            <div class="aspect-[4/5] overflow-hidden bg-gray-100">
                                <img src="{{ $related->image_url }}"
                                     alt="{{ $related->name }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-gray-900 group-hover:text-accent transition-colors duration-200 line-clamp-1">{{ $related->name }}</h3>
                                <p class="mt-1 text-base font-bold text-accent">{{ $related->formatted_price }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </section>
</div>
