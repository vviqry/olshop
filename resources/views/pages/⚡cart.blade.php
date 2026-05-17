<?php
use Livewire\Component;
use App\Models\Product;

new class extends Component {
    public array $cartItems = [];
    public int $total = 0;

    public function mount(): void
    {
        $this->loadCart();
    }

    public function loadCart(): void
    {
        $cart = session('cart', []);
        $this->cartItems = [];

        if (empty($cart)) {
            $this->total = 0;
            return;
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $total = 0;
        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);
            if (!$product) continue;

            $subtotal = $product->price * $quantity;
            $total += $subtotal;
            $this->cartItems[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image_url' => $product->image_url,
                'price' => $product->price,
                'formatted_price' => $product->formatted_price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'formatted_subtotal' => 'Rp' . number_format($subtotal, 0, ',', '.'),
            ];
        }

        $this->total = $total;
    }

    public function updateQuantity(int $productId, string $action): void
    {
        $cart = session('cart', []);

        if (!isset($cart[$productId])) return;

        if ($action === 'increase') {
            $cart[$productId]++;
        } elseif ($action === 'decrease') {
            $cart[$productId]--;
            if ($cart[$productId] <= 0) {
                unset($cart[$productId]);
            }
        }

        session(['cart' => $cart]);
        $this->loadCart();
        $this->dispatch('cart-updated', count: count(session('cart', [])));
    }

    public function removeItem(int $productId): void
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);
        $this->loadCart();
        $this->dispatch('cart-updated', count: count(session('cart', [])));
    }

    public function checkout(): void
    {
        $cart = session('cart', []);

        if (empty($cart)) return;

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $message = "Halo yStore!%0A%0ASaya ingin memesan:%0A";
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);
            if (!$product) continue;
            $subtotal = $product->price * $quantity;
            $total += $subtotal;
            $message .= "- {$product->name} x{$quantity} = Rp" . number_format($subtotal, 0, ',', '.') . "%0A";
        }

        $message .= "%0ATotal: Rp" . number_format($total, 0, ',', '.');
        $message .= "%0A%0ATerima kasih!";

        $this->redirect("https://wa.me/6281234567890?text={$message}");
    }

    public function render()
    {
        return view('pages.⚡cart');
    }
};
?>

<div class="animate-fade-in">
    <section class="bg-gradient-to-r from-accent via-secondary to-primary py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-white">Keranjang</h1>
            <p class="mt-2 text-white/80">Daftar belanjaan kamu</p>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(empty($cartItems))
            <div class="text-center py-16">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-20 text-gray-300 mx-auto">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                <h2 class="mt-6 text-xl font-semibold text-gray-900">Keranjang Kosong</h2>
                <p class="mt-2 text-gray-500">Belum ada produk yang ditambahkan ke keranjang</p>
                <a href="{{ route('products') }}"
                   class="inline-flex items-center gap-2 mt-6 bg-accent hover:bg-secondary text-white font-semibold px-8 py-3 rounded-full transition-all duration-200">
                    Mulai Belanja
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($cartItems as $item)
                    <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm flex gap-4 items-center transition-all duration-200 hover:shadow-md">
                        <a href="{{ route('product.detail', $item['slug']) }}" class="shrink-0">
                            <div class="size-20 sm:size-24 rounded-xl overflow-hidden bg-gray-100">
                                <img src="{{ $item['image_url'] }}"
                                     alt="{{ $item['name'] }}"
                                     class="w-full h-full object-cover">
                            </div>
                        </a>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('product.detail', $item['slug']) }}">
                                <h3 class="text-sm font-semibold text-gray-900 hover:text-accent transition-colors duration-200 line-clamp-1">{{ $item['name'] }}</h3>
                            </a>
                            <p class="text-sm font-bold text-accent mt-1">{{ $item['formatted_price'] }}</p>

                            <div class="flex items-center gap-3 mt-3">
                                <button wire:click="updateQuantity({{ $item['product_id'] }}, 'decrease')"
                                        class="size-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-accent transition-all duration-200 active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                    </svg>
                                </button>
                                <span class="text-sm font-semibold w-6 text-center">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $item['product_id'] }}, 'increase')"
                                        class="size-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-accent transition-all duration-200 active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-bold text-gray-900">{{ $item['formatted_subtotal'] }}</p>
                            <button wire:click="removeItem({{ $item['product_id'] }})"
                                    class="mt-2 text-xs text-red-500 hover:text-red-700 transition-colors duration-200">
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Total & Checkout --}}
            <div class="mt-8 bg-white rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Total</span>
                    <span class="text-2xl font-bold text-accent">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </div>
                @if($total < 200000 && $total > 0)
                    <p class="mt-2 text-xs text-orange-600 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        Belanja Rp{{ number_format(200000 - $total, 0, ',', '.') }} lagi untuk gratis ongkir!
                    </p>
                @elseif($total >= 200000)
                    <p class="mt-2 text-xs text-green-600 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Selamat! Kamu dapat gratis ongkir!
                    </p>
                @endif
                <button wire:click="checkout"
                        class="mt-4 w-full bg-accent hover:bg-secondary text-white font-semibold py-3.5 rounded-xl transition-all duration-200 hover:shadow-lg active:scale-[0.98]">
                    Checkout via WhatsApp
                </button>
            </div>
        @endif
    </section>
</div>
