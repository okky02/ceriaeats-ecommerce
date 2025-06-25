<section class="bg-gradient-to-r from-pink-300 via-white to-pink-200 py-10 lg:py-20 px-4">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 items-center gap-10">

        <!-- Text Content -->
        <div class="px-4 sm:px-5 lg:px-8 bg-gradient-to-r from-white/0 via-white to-white/0">
            <div class="space-y-6 text-center lg:text-left p-6 md:p-10 max-w-3xl mx-auto">
                <h2 class="text-4xl md:text-7xl font-extrabold text-pink-600">READY TO EAT?</h2>
                <h3 class="text-3xl md:text-6xl font-bold text-gray-800">CeriaEats.</h3>
                <p class="text-base md:text-xl text-gray-600">
                    Temukan menu favoritmu & pesan sekarang!
                </p>
                <a href="{{ route('login') }}"
                    class="bg-yellow-400 hover:bg-pink-500 text-white text-lg font-bold md:text-xl py-3 px-7 rounded-full shadow-lg transition duration-300 inline-block">
                    Order Now
                </a>
            </div>
        </div>

        <!-- Carousel -->
        <div class="relative flex justify-center items-center">
            <div id="default-carousel" class="relative w-full h-60 sm:h-70 md:h-80 lg:h-[450px]" data-carousel="slide">

                <!-- Image Container -->
                <div class="relative h-full overflow-hidden rounded-2xl border-4 border-pink-100 shadow-xl">
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="{{ asset('img/hero/1.png') }}" alt="hero-produk1"
                            class="absolute w-full h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    </div>
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="{{ asset('img/hero/2.jpg') }}" alt="hero-produk2"
                            class="absolute w-full h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    </div>
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="{{ asset('img/hero/3.png') }}" alt="hero-produk3"
                            class="absolute w-full h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    </div>
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="{{ asset('img/hero/4.png') }}" alt="hero-produk4"
                            class="absolute w-full h-full object-cover top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    </div>
                </div>

                <!-- Indicators -->
                <div class="absolute bottom-2 left-1/2 -translate-x-1/2 z-30 flex space-x-2">
                    <button type="button" class="w-3 h-3 rounded-full opacity-50 hover:opacity-100 transition"
                        aria-current="true" data-carousel-slide-to="0" aria-label="Slide 1"></button>
                    <button type="button" class="w-3 h-3 rounded-full opacity-50 hover:opacity-100 transition"
                        aria-current="false" data-carousel-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" class="w-3 h-3 rounded-full opacity-50 hover:opacity-100 transition"
                        aria-current="false" data-carousel-slide-to="2" aria-label="Slide 3"></button>
                    <button type="button" class="w-3 h-3 rounded-full opacity-50 hover:opacity-100 transition"
                        aria-current="false" data-carousel-slide-to="2" aria-label="Slide 4"></button>
                </div>


                <!-- Controls -->
                <button type="button" data-carousel-prev
                    class="absolute top-1/2 left-[-50px] z-30 flex items-center justify-center h-12 w-12 cursor-pointer group focus:outline-none">
                    <span
                        class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-400 hover:bg-pink-500 transition-all duration-200 group-hover:ring-yellow-400">
                        <i class="fas fa-chevron-left text-white group-hover:text-white text-lg"></i>
                    </span>
                </button>

                <button type="button" data-carousel-next
                    class="absolute top-1/2 right-[-50px] z-30 flex items-center justify-center h-12 w-12 cursor-pointer group focus:outline-none">
                    <span
                        class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-400 hover:bg-pink-500 transition-all duration-200 group-hover:ring-yellow-400">
                        <i class="fas fa-chevron-right text-white group-hover:text-white text-lg"></i>
                    </span>
                </button>

            </div>
        </div>

    </div>
</section>
