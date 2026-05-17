<?php
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Models\Product;

new class extends Component {
    use WithPagination;

    public ?string $selectedCategory = null;

    protected $queryString = ['selectedCategory' => ['except' => null]];

    public function mount(): void
    {
        $this->selectedCategory = request()->query('category', $this->selectedCategory);
    }

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);
        if (!$product) return;

        $cart = session('cart', []);
        $cart[$productId] = ($cart[$productId] ?? 0) + 1;
        session(['cart' => $cart]);

        session()->flash('message', 'Produk ditambahkan ke keranjang!');
    }

    public function filterByCategory(?string $slug): void
    {
        $this->selectedCategory = $slug;
        $this->resetPage();
    }

    public function render()
    {
        return view('pages.⚡products', [
            'categories' => Category::all(),
            'products' => Product::with('category')
                ->when($this->selectedCategory, function ($query) {
                    return $query->whereHas('category', function ($q) {
                        $q->where('slug', $this->selectedCategory);
                    });
                })
                ->latest()
                ->paginate(12),
        ]);
    }
};
?>

<div class="animate-fade-in">
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-accent via-secondary to-primary py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-white">Produk</h1>
            <p class="mt-2 text-white/80">Koleksi lengkap pakaian pria yStore</p>
        </div>
    </section>

    {{-- Products Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Filter Categories --}}
        <div class="flex flex-wrap gap-2 mb-8">
            <button wire:click="filterByCategory(null)"
                    class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ is_null($selectedCategory) ? 'bg-accent text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 shadow-sm' }}">
                Semua
            </button>
            @foreach($categories as $category)
                <button wire:click="filterByCategory('{{ $category->slug }}')"
                        class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ $selectedCategory === $category->slug ? 'bg-accent text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-100 shadow-sm' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <a href="{{ route('product.detail', $product->slug) }}" class="block aspect-[4/5] overflow-hidden bg-gray-100">
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </a>
                    <div class="p-4">
                        <a href="{{ route('product.detail', $product->slug) }}">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $product->category->name }}</p>
                            <h3 class="mt-1 text-sm font-semibold text-gray-900 group-hover:text-accent transition-colors duration-200 line-clamp-1">{{ $product->name }}</h3>
                            <p class="mt-2 text-base font-bold text-accent">{{ $product->formatted_price }}</p>
                        </a>
                        <button wire:click="addToCart({{ $product->id }})"
                                class="mt-3 w-full bg-accent hover:bg-secondary text-white text-sm font-semibold py-2.5 rounded-xl transition-all duration-200 hover:shadow-md active:scale-95">
                            + Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-16 text-gray-300 mx-auto">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                    <p class="mt-4 text-gray-500">Tidak ada produk ditemukan</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </section>
</div>
