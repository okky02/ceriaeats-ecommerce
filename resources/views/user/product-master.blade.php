@extends('layouts.user')

@section('content')
    <section class="bg-pink-50 py-12 px-4 lg:px-8">
        @if (session('success'))
            <x-success-toast title="Success!!" message="{{ session('success') }}" />
        @endif

        @if (session('error_title'))
            <x-error-toast title="{{ session('error_title') }}" message="{{ session('error_message') }}" />
        @endif
        <!-- Produk: Gambar + Detail -->
        <div class="max-w-screen-xl mx-auto bg-white rounded-2xl shadow-md p-8 mb-12">
            <!-- Tombol Kembali -->
            <a href="{{ route('user.shop.index') }}"
                class="inline-flex items-center mb-6 text-md text-pink-500 font-semibold hover:underline hover:text-yellow-500 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back To Shop
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Gambar -->
                <div class="col-span-1 aspect-square overflow-hidden rounded-xl shadow-md">
                    <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}"
                        class="w-full h-full object-cover">
                </div>

                <!-- Detail Produk -->
                <div class="col-span-2 flex flex-col justify-between">
                    <div>
                        <h3 class="text-3xl font-bold text-pink-600 mb-2">{{ $product->nama_produk }}</h3>
                        <p class="text-xl font-semibold text-gray-800 mb-4">
                            Rp{{ number_format($product->harga, 0, ',', '.') }}</p>

                        <div class="flex items-center text-yellow-400 mb-4">
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    $fillPercentage = 0;
                                    if ($i <= floor($averageRating)) {
                                        $fillPercentage = 100;
                                    } elseif ($i == floor($averageRating) + 1) {
                                        $fillPercentage = ($averageRating - floor($averageRating)) * 100;
                                    }
                                @endphp
                                <div class="relative w-5 h-5 mr-1">
                                    <i class="far fa-star absolute top-0 left-0 w-full h-full"></i>
                                    <i class="fas fa-star absolute top-0 left-0 h-full overflow-hidden"
                                        style="width: {{ $fillPercentage }}%"></i>
                                </div>
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">{{ number_format($averageRating, 1) }}
                                ({{ $reviewCount }} reviews)</span>
                        </div>


                        <p class="text-gray-700 leading-relaxed mb-4">
                            {{ $product->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </p>

                    </div>

                    <div class="mt-6 sm:flex-row items-center gap-4 sm:gap-6">
                        <!-- Quantity -->
                        <div class="mb-4">
                            <span class="block text-sm font-medium text-gray-700 mb-3">Quantity:</span>

                            <div class="bg-gray-100 rounded-md flex items-center justify-between px-3 py-1 w-[100px]">
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
                        </div>

                        <form x-data="{ loading: false }" @submit="loading = true" action="{{ route('user.cart.add') }}"
                            method="POST" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="cartQuantityInput" value="1"
                                class="shop-cart-qty-input">

                            <x-spinner-button loading="loading" label="Add to Cart" loadingLabel="adding to cart..."
                                bgColor="bg-pink-500" hoverColor="hover:bg-pink-600" ringColor="focus:ring-pink-600"
                                textSize="text-lg" icon='<i class="fas fa-shopping-bag mr-2"></i>'
                                onclick="addToCart({{ $product->id }})" />
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Section: Form & Daftar Review -->
        <div class="max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Review -->
            <div class="col-span-1 bg-white rounded-2xl shadow-md p-6 h-fit sticky top-[115px]">
                <h4 class="text-xl font-semibold text-pink-600 mb-3">Leave a Review</h4>
                <form id="reviewForm" x-data="{ loading: false }" @submit="loading = true"
                    action="{{ route('user.product.review.store', $product->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rating:</label>
                        <div id="starRating2" class="flex gap-1 text-2xl text-gray-300 cursor-pointer">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star star2" data-value="{{ $i }}"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating2" id="rating2" value="">
                    </div>

                    <div class="mb-4">
                        <label for="review2" class="block text-sm font-medium text-gray-700 mb-1">Your Review:</label>
                        <textarea name="review2" id="review2" rows="3"
                            class="w-full rounded-md border-gray-300 focus:ring-pink-400 focus:border-pink-400"
                            placeholder="Tulis pengalaman kamu..."></textarea>
                    </div>

                    <x-spinner-button loading="loading" label="Submit Review" loadingLabel="Save review..." bgColor="bg-pink-500"
                        hoverColor="hover:bg-pink-600" ringColor="focus:ring-pink-600" textSize="text-sm"
                        icon='<i class="fa-solid fa-floppy-disk mr-2 "></i>' />
                </form>
            </div>

            <!-- Daftar Review -->
            <div class="col-span-2 bg-white rounded-2xl shadow-md p-6">
                <h4 class="text-xl font-semibold text-pink-600 mb-3">Reviews</h4>
                <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                    @if ($reviews->isEmpty())
                        <p class="text-gray-500 italic">Belum ada review untuk produk ini. Jadilah yang pertama!</p>
                    @else
                        @foreach ($reviews as $review)
                            <div class="mb-4">
                                <div class="flex items-start">
                                    <!-- Bagian Foto Profil -->
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                        @if ($review->user->profile_photo)
                                            <img src="{{ asset('storage/' . $review->user->profile_photo) }}"
                                                alt="Profile Photo" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <i class="fas fa-user-circle text-pink-600 text-[36px]"></i>
                                        @endif
                                    </div>

                                    <!-- Bagian Konten Review -->
                                    <div class="flex-1">
                                        <div class="flex justify-between items-center mb-1">
                                            <div>
                                                <strong class="text-gray-800">{{ $review->user->name }}</strong>
                                            </div>
                                            <span
                                                class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>

                                        <div class="text-yellow-400 mb-1">
                                            @for ($i = 0; $i < 5; $i++)
                                                <i class="{{ $i < $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                            @endfor
                                        </div>

                                        <p class="text-gray-700 text-sm">{{ $review->review ?? '-' }}</p>

                                        @if ($review->user_id === auth()->id())
                                            <form action="{{ route('user.product.review.delete', $review->id) }}"
                                                method="POST" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-sm text-red-500 hover:underline">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </section>

    <script>
        // Rating
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('#starRating2 .star2');
            const ratingInput = document.getElementById('rating2');
            const form = document.getElementById('reviewForm');

            if (!stars.length || !ratingInput || !form) return;

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    ratingInput.value = value;

                    stars.forEach(s => {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                        if (s.getAttribute('data-value') <= value) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-400');
                        }
                    });
                });
            });
        });
    </script>

@endsection
