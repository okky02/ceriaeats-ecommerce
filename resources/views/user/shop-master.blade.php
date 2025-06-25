@extends('layouts.user')

@section('content')
<section class="flex-grow flex flex-col  bg-pink-50">
    <div class="mx-auto w-full max-w-screen-xl py-10 px-4 lg:px-6">
            @if (session('success'))
                <x-success-toast title="Success!!" message="{{ session('success') }}" />
            @endif

            @if (session('error_title'))
                <x-error-toast title="{{ session('error_title') }}" message="{{ session('error_message') }}" />
            @endif

            <!-- Header -->
            <div class="mb-10 text-center">
                <h2 class="text-4xl font-extrabold text-pink-600">Shop</h2>
                <p class="text-gray-600 mt-2 text-lg">Temukan produk favoritmu di sini</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Sidebar -->
                <aside class="lg:col-span-3 bg-white rounded-2xl shadow p-6 border border-pink-100 space-y-6">
                    <!-- Kategori -->
                    <div>
                        <h3 class="text-2xl font-bold text-pink-500 mb-4">Categories</h3>
                        <ul class="space-y-3">
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['category_id' => null]) }}"
                                    class="group flex items-center justify-between transition {{ request('category_id') ? 'text-gray-700' : 'text-pink-600 font-semibold' }}">
                                    <span class="group-hover:text-pink-600">All</span>
                                    <i
                                        class="fas fa-chevron-right text-sm {{ request('category_id') ? 'text-gray-400 group-hover:text-pink-600' : 'text-pink-600' }}"></i>
                                </a>
                            </li>

                            @foreach ($categories as $category)
                                <li>
                                    <a href="{{ request()->fullUrlWithQuery(['category_id' => $category->id]) }}"
                                        class="group flex items-center justify-between transition {{ request('category_id') == $category->id ? 'text-pink-600 font-semibold' : 'text-gray-700' }}">
                                        <span class="group-hover:text-pink-600">{{ $category->nama_kategori }}</span>
                                        <i
                                            class="fas fa-chevron-right text-sm {{ request('category_id') == $category->id ? 'text-pink-600' : 'text-gray-400 group-hover:text-pink-600' }}"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>

                <!-- Produk -->
                <div class="lg:col-span-9 space-y-8">
                    <div class="flex justify-between items-center mb-4">
                        <!-- Show -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-pink-600">Show:</span>
                            <form id="perPageForm" method="GET">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                <input type="hidden" name="sort" value="{{ request('sort') }}">

                                <select name="perPage" onchange="document.getElementById('perPageForm').submit()"
                                    class="border border-pink-400 rounded-lg px-3 py-2 text-sm focus:ring-pink-500 focus:border-pink-500">
                                    <option value="8" {{ request('perPage') == 8 ? 'selected' : '' }}>8</option>
                                    <option value="16" {{ request('perPage') == 16 ? 'selected' : '' }}>16</option>
                                    <option value="32" {{ request('perPage') == 32 ? 'selected' : '' }}>32</option>
                                    <option value="all" {{ request('perPage') == 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </form>

                            <span class="text-sm text-pink-600">entries</span>
                        </div>

                        <div class="relative flex items-center space-x-2">
                            <form method="GET" action="" class="flex items-center space-x-2 relative">
                                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                <input type="hidden" name="perPage" value="{{ request('perPage', 8) }}">

                                <!-- Input Search -->
                                <div class="relative">
                                    <input type="text" name="search" placeholder="Search..."
                                        value="{{ request('search') }}"
                                        class="border rounded-lg px-4 py-2 border-pink-400 pl-10 text-sm focus:ring-pink-500 focus:border-pink-500">
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-pink-500"></i>
                                </div>

                                <!-- Sort Dropdown -->
                                <div class="relative">
                                    <select name="sort"
                                        class="border rounded-lg px-4 py-2 border-pink-400 text-sm focus:ring-pink-500 focus:border-pink-500"
                                        onchange="this.form.submit()">
                                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Latest
                                        </option>
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Oldest
                                        </option>
                                    </select>
                                </div>
                            </form>

                        </div>
                    </div>

                    <!-- Grid Produk -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse ($products as $product)
                            <div
                                class="block bg-white rounded-2xl overflow-hidden shadow hover:shadow-xl transition hover:-translate-y-1">

                                <div class="relative">
                                    <a href="{{ route('user.shop.show', $product->id) }}">
                                        <img src="{{ asset('storage/' . $product->gambar) }}"
                                            alt="{{ $product->nama_produk }}" class="h-44 w-full object-cover">
                                    </a>
                                </div>

                                <div class="p-4 space-y-1">
                                    <a href="{{ route('user.shop.show', $product->id) }}">
                                        <p class="text-pink-600 font-semibold text-base truncate">
                                            {{ $product->nama_produk }}</p>
                                    </a>

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

                                    <div class="flex flex-col gap-2 pt-3">
                                        <p class="text-gray-800 font-bold text-sm">
                                            Rp{{ number_format($product->harga, 0, ',', '.') }}
                                        </p>

                                        <div>
                                            <form method="POST" action="{{ route('user.cart.add') }}"
                                                class="flex items-center justify-between gap-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1"
                                                    class="shop-cart-qty-input">

                                                <div
                                                    class="bg-gray-100 rounded-full flex items-center justify-between px-3 py-1 w-[90px]">
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
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 col-span-full py-10">No products found.</p>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $products->appends(request()->except('page'))->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
