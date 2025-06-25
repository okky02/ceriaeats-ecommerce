<!-- Testimonials -->
<section id="testimonials">
    <div class="bg-pink-50 mx-auto w-full max-w-screen-xl px-4 sm:px-6 py-6 lg:py-12 text-center">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-pink-600">What Our Customers Say</h2>
        </div>

        <div class="flex justify-center">
            <!-- Testimonial Slider Container -->
            <div class="w-full max-w-xl" x-data="{
                current: 0,
                testimonials: [0, 1, 2],
                init() {
                    setInterval(() => {
                        this.current = (this.current + 1) % this.testimonials.length;
                    }, 3000); // ganti 5000 jadi 3000 kalau mau lebih cepat
                }
            }">
                <div class="relative">
                    <!-- Testimonial Slides -->
                    <div class="overflow-hidden relative h-80">

                        <!-- Slide 1 -->
                        <div class="absolute inset-0 transition-all duration-500 ease-in-out p-8 bg-white rounded-3xl border border-pink-300 flex flex-col justify-center"
                            :class="{ 'opacity-100 translate-x-0': current === 0, 'opacity-0 translate-x-full': current !== 0 }">
                            <div class="flex justify-center mb-4 text-yellow-400 space-x-1">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="text-gray-700 italic mb-6 text-lg">"I love my swing set! CeriaEats helped me
                                dengan design & planning. Ramah, cepat, dan hasilnya memuaskan!"</p>
                            <div class="flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-300 flex-shrink-0">
                                    <img src="{{ asset('img/testimonials/evan.jpg') }}" alt="Foto pelanggan"
                                        class="object-cover w-full h-full" />
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-pink-600">Fauzan Evan</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="absolute inset-0 transition-all duration-500 ease-in-out p-8 bg-white rounded-3xl border border-pink-300 flex flex-col justify-center"
                            :class="{ 'opacity-100 translate-x-0': current === 1, 'opacity-0 translate-x-full': current !== 1 }">
                            <div class="flex justify-center mb-4 text-yellow-400 space-x-1">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="text-gray-700 italic mb-6 text-lg">"Proses cepat dan hasil sangat memuaskan.
                                Anak-anak suka dan kami puas banget!"</p>
                            <div class="flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-300 flex-shrink-0">
                                    <img src="{{ asset('img/testimonials/irfan.jpg') }}" alt="Foto pelanggan"
                                        class="object-cover w-full h-full" />
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-pink-600">Irfan Maulana</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="absolute inset-0 transition-all duration-500 ease-in-out p-8 bg-white rounded-3xl border border-pink-300 flex flex-col justify-center"
                            :class="{ 'opacity-100 translate-x-0': current === 2, 'opacity-0 translate-x-full': current !== 2 }">
                            <div class="flex justify-center mb-4 text-yellow-400 space-x-1">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="text-gray-700 italic mb-6 text-lg">"Layanan terbaik! Sudah bertahun-tahun dan
                                tetap awet. Worth it!"</p>
                            <div class="flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-300 flex-shrink-0">
                                    <img src="{{ asset('img/testimonials/johannes.jpg') }}" alt="Foto pelanggan"
                                        class="object-cover w-full h-full" />
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-pink-600">Johannes TM</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <button @click="current = (current - 1 + testimonials.length) % testimonials.length"
                        class="absolute top-1/2 left-0 -translate-y-1/2 -translate-x-6 w-12 h-12 rounded-full bg-yellow-400 hover:bg-pink-500 text-white shadow-lg flex items-center justify-center transition group">
                        <i class="fas fa-chevron-left text-lg"></i>
                    </button>

                    <button @click="current = (current + 1) % testimonials.length"
                        class="absolute top-1/2 right-0 -translate-y-1/2 translate-x-6 w-12 h-12 rounded-full bg-yellow-400 hover:bg-pink-500 text-white shadow-lg flex items-center justify-center transition group">
                        <i class="fas fa-chevron-right text-lg"></i>
                    </button>
                </div>

                <!-- Dots Indicator -->
                <div class="flex justify-center mt-6 space-x-2">
                    <template x-for="(_, index) in testimonials" :key="index">
                        <button @click="current = index"
                            class="w-3 h-3 rounded-full transition-all duration-300"
                            :class="{ 'bg-yellow-400 scale-125 shadow-md': current === index, 'bg-yellow-200': current !==
                                index }">
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>
