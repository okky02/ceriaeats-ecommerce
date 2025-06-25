<section>
    @if (session('success'))
        <x-success-toast title="Success!!" message="{{ session('success') }}" />
    @endif

    @if (session('error_title'))
        <x-error-toast title="{{ session('error_title') }}" message="{{ session('error_message') }}" />
    @endif
    <div class="bg-pink-50 mx-auto w-full max-w-screen-xl py-6 lg:py-8">
        <div class="px-5 mb-6 text-center">
            <h2 class="text-3xl font-bold text-pink-600">Products</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 px-4">
            @forelse ($products as $product)
                <div
                    class="block bg-white rounded-2xl overflow-hidden shadow hover:shadow-xl transition hover:-translate-y-1">
                    <a href="{{ route('user.shop.show', $product->id) }}">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}"
                                class="h-44 w-full object-cover">
                        </div>

                        <div class="p-4 space-y-1">
                            <p class="text-pink-600 font-semibold text-base truncate">{{ $product->nama_produk }}</p>

                            @php
                                $reviewCount = $product->reviews->count();
                                $averageRating = $reviewCount > 0 ? $product->reviews->avg('rating') : 5.0;
                            @endphp

                            <div class="flex items-center text-yellow-400 text-xs">
                                @for ($i = 1; $i <= 5; $i++)
                                    @php
                                        $fillPercentage = 0;
                                        if ($i <= floor($averageRating)) {
                                            $fillPercentage = 100;
                                        } elseif ($i == floor($averageRating) + 1) {
                                            $fillPercentage = ($averageRating - floor($averageRating)) * 100;
                                        }
                                    @endphp
                                    <div class="relative w-4 h-4 mr-0.5">
                                        <i class="far fa-star absolute top-0 left-0 w-full h-full"></i>
                                        <i class="fas fa-star absolute top-0 left-0 h-full overflow-hidden"
                                            style="width: {{ $fillPercentage }}%"></i>
                                    </div>
                                @endfor
                                <span class="ml-1 text-gray-500">{{ number_format($averageRating, 1) }}
                                    ({{ $reviewCount }})
                                </span>
                            </div>

                            <p class="text-gray-800 font-bold text-sm pt-2">
                                Rp{{ number_format($product->harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>

                    <!-- Quantity & Add to Cart -->
                    <div class="px-4 pb-4 pt-0">
                        <form method="POST" action="{{ route('user.cart.add') }}"
                            class="flex items-center justify-between gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1" class="shop-cart-qty-input">

                            <div class="bg-gray-100 rounded-full flex items-center justify-between px-3 py-1 w-[90px]">
                                <button type="button" class="text-pink-500 hover:text-pink-700"
                                    onclick="decreaseQuantity(this)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="text-gray-800 font-semibold text-sm quantity">1</span>
                                <button type="button" class="text-pink-500 hover:text-pink-700"
                                    onclick="increaseQuantity(this)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <button type="submit"
                                class="bg-pink-500 hover:bg-yellow-400 text-white w-10 h-10 rounded-full flex items-center justify-center transition"
                                title="Add to Cart">
                                <i class="fas fa-shopping-bag fa-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 col-span-full py-10">No products found.</p>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mx-5 mt-10">
            {{ $products->appends(request()->except('page'))->links('pagination::tailwind') }}
        </div>
    </div>
</section>
