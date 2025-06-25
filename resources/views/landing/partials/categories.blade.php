<section>
    <div class="bg-pink-50 mx-auto w-full max-w-screen-xl py-10 px-4 lg:py-12 ">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-pink-600">Categories</h2>

            <!-- Tombol Panah -->
            <div class="flex space-x-3">
                <!-- Tombol Kiri -->
                <button id="prev" type="button"
                    class="w-12 h-12 rounded-full bg-yellow-400 hover:bg-pink-500 text-white shadow-lg transition duration-300 flex items-center justify-center">
                    <i class="fas fa-chevron-left text-lg"></i>
                </button>

                <!-- Tombol Kanan -->
                <button id="next" type="button"
                    class="w-12 h-12 rounded-full bg-yellow-400 hover:bg-pink-500 text-white shadow-lg transition duration-300 flex items-center justify-center">
                    <i class="fas fa-chevron-right text-lg"></i>
                </button>
            </div>

        </div>


        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6">
            @php
                $isAllActive = request()->missing('category_id');
                $activeCardClass = $isAllActive ? 'ring-2 ring-pink-500' : '';
                $activeCircleClass = $isAllActive ? 'ring-2 ring-pink-500' : '';
            @endphp
            <div
                class="category-item group bg-white rounded-2xl border-2 hover:border-pink-500 shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300 p-5 text-center {{ $activeCardClass }}">
                <a href="{{ route('landing.index') }}">
                    <div
                        class="mx-auto mb-4 w-28 h-28 flex items-center justify-center bg-pink-100 rounded-full border-2 border-pink-300 group-hover:border-pink-500 shadow-sm transition-all duration-300 {{ $activeCircleClass }}">
                        <span class="text-2xl text-pink-600 font-bold">All</span>
                    </div>
                    <h3 class="text-lg font-semibold text-pink-600">Semua Produk</h3>
                </a>
            </div>
            @php
                $activeCategoryId = request('category_id');
            @endphp
            @forelse ($categories as $category)
                @php
                    $isActive = $activeCategoryId == $category->id;
                    $activeCardClass = $isActive ? 'ring-2 ring-pink-500' : '';
                    $activeImageClass = $isActive ? 'ring-2 ring-pink-500' : '';
                @endphp
                <div
                    class="category-item group bg-white rounded-2xl shadow-md border-2 hover:border-pink-500 hover:shadow-xl transform hover:scale-105 transition-all duration-300 p-5 text-center {{ $activeCardClass }}">
                    <a href="{{ route('landing.index', ['category_id' => $category->id]) }}">
                        <img src="{{ asset('storage/' . $category->gambar) }}" alt="{{ $category->nama_kategori }}"
                            class="mx-auto mb-4 w-28 h-28 object-cover rounded-full border-2 border-pink-300 group-hover:border-pink-500 transition-all duration-300 shadow-sm {{ $activeImageClass }}">
                        <h3 class="text-lg font-semibold text-pink-600">{{ $category->nama_kategori }}</h3>
                    </a>
                </div>
            @empty
                <p class="text-center text-gray-500 col-span-full py-10">No categories found.</p>
            @endforelse

        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let categories = document.querySelectorAll(".category-item");
        let currentIndex = 0;

        const visibleCount = 5;

        // Coba ambil index aktif dari localStorage
        const storedIndex = localStorage.getItem("categoryStartIndex");
        if (storedIndex) {
            currentIndex = parseInt(storedIndex);
        }

        function showCategories() {
            categories.forEach((category, index) => {
                category.style.display = "none";
            });

            for (let i = currentIndex; i < currentIndex + visibleCount && i < categories.length; i++) {
                categories[i].style.display = "block";
            }
        }

        document.getElementById("prev").addEventListener("click", function() {
            if (currentIndex > 0) {
                currentIndex--;
                localStorage.setItem("categoryStartIndex", currentIndex);
                showCategories();
            }
        });

        document.getElementById("next").addEventListener("click", function() {
            if (currentIndex + visibleCount < categories.length) {
                currentIndex++;
                localStorage.setItem("categoryStartIndex", currentIndex);
                showCategories();
            }
        });

        // Simpan posisi saat user klik kategori
        categories.forEach((category, index) => {
            category.addEventListener("click", function() {
                localStorage.setItem("categoryStartIndex", currentIndex);
            });
        });

        showCategories();
    });
</script>
