@extends('layouts.user')

@section('content')
    <section class="flex-grow bg-pink-50 flex items-center">
        <div class="mx-auto w-full max-w-screen-xl py-12 px-4 lg:px-6">

            <!-- Hero Section with Image -->
            <div class="flex flex-col lg:flex-row items-center mb-16 gap-8">
                <div class="lg:w-1/2">
                    <h1 class="text-5xl font-bold text-pink-600 leading-tight mb-4">About Us</h1>
                    <p class="text-xl text-gray-600 mb-6 leading-relaxed">
                        CeriaEats is all about bringing the joy of traditional Indonesian food to your everyday life, memadukan resep warisan dengan sentuhan modern sekaligus menghadirkan layanan katering untuk berbagai momen spesialmu.
                    </p>
                    <div class="flex gap-4">
                        <a href="#values"
                            class="border-2 border-pink-600 text-pink-600 hover:bg-pink-50 px-6 py-3 rounded-full font-medium transition-all duration-300">
                            Our Values
                        </a>
                    </div>
                </div>
                <div class="lg:w-1/2 relative">
                    <div class="relative rounded-2xl overflow-hidden shadow-xl h-96">
                        <img src="{{ asset('img/gallery/13.png') }}"
                            alt="Our team working together" class="w-full h-full object-cover object-center">
                        <div class="absolute inset-0 bg-gradient-to-t from-pink-600/20 to-pink-400/20"></div>
                    </div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-pink-200 rounded-2xl -z-10"></div>
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-pink-100 rounded-full -z-10"></div>
                </div>
            </div>

            <!-- Mission & Values -->
            <div id="values" class="bg-white p-8 rounded-3xl shadow-lg mb-16">
                <div class="grid md:grid-cols-2 gap-12">
                    <!-- Our Mission -->
                    <div>
                        <div class="flex items-center mb-6">
                            <div class="bg-pink-100 w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-arrow-trend-up text-pink-600 text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Our Mission</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed">
                            Misi kami adalah membuat makanan tradisional tetap relevan dan disukai semua generasi. We
                            deliver taste, warmth, and nostalgia — straight to your table.
                        </p>
                    </div>

                    <!-- Our Vision -->
                    <div>
                        <div class="flex items-center mb-6">
                            <div class="bg-pink-100 w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-eye text-pink-600 text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Our Vision</h3>
                        </div>
                        <p class="text-gray-600 leading-relaxed">
                            To be the go-to brand for modern Indonesian comfort food — simple, soulful, and unforgettable.
                            Kami ingin setiap suapan membawa rasa bangga akan kuliner lokal.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Why Choose Us -->
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden mb-16">
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/2 bg-gradient-to-br from-pink-600 to-pink-400 p-12 text-white">
                        <h2 class="text-3xl font-bold mb-6">Why CeriaEats?</h2>
                        <p class="text-pink-100 mb-8 text-lg leading-relaxed">
                            Karena kami menyajikan makanan lokal dengan cara yang fresh, playful, dan tetap otentik. From
                            kitchen to your doorstep, rasa selalu jadi prioritas.
                        </p>
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="bg-pink-100/20 w-10 h-10 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-check text-white text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg">Authentic Taste</h4>
                                    <p class="text-pink-100">Resep turun-temurun, rasa yang bikin kangen.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-pink-100/20 w-10 h-10 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-check text-white text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg">Fun & Modern Branding</h4>
                                    <p class="text-pink-100">Tampilan kekinian tanpa menghilangkan akar budaya</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="bg-pink-100/20 w-10 h-10 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-check text-white text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg">Fresh Ingredients</h4>
                                    <p class="text-pink-100">Kami percaya makanan enak dimulai dari bahan terbaik.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:w-1/2 p-12">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Our Values</h3>
                        <div class="space-y-8">
                            <div>
                                <h4 class="text-lg font-semibold text-pink-600 mb-2">Creativity</h4>
                                <p class="text-gray-600">Selalu berinovasi dalam menyajikan makanan tradisional secara fun
                                    dan unik.</p>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-pink-600 mb-2">Authenticity</h4>
                                <p class="text-gray-600">Kami menjaga rasa asli Indonesia di setiap menu.</p>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-pink-600 mb-2">Customer Happiness</h4>
                                <p class="text-gray-600">Kami percaya, makanan enak bisa bikin hari lebih ceria!</p>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-pink-600 mb-2">Quality First</h4>
                                <p class="text-gray-600">Kami nggak pernah kompromi soal kualitas bahan dan pelayanan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Section -->
            <div class="mb-16">
                <h2 class="text-3xl font-bold text-center text-pink-600 mb-12">Gallery</h2>


                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="grid gap-4">
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/2.png') }}" alt="1">
                        </div>
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/3.png') }}" alt="2">
                        </div>
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/12.png') }}"
                                alt="3">
                        </div>
                    </div>
                    <div class="grid gap-4">
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/7.png') }}" alt="4">
                        </div>
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/8.png') }}" alt="5">
                        </div>
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/11.png') }}"
                                alt="6">
                        </div>
                    </div>
                    <div class="grid gap-4">
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/6.png') }}" alt="7">
                        </div>
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/4.png') }}" alt="8">
                        </div>
                        <div>
                            <img class="h-auto max-w-full rounded-lg"
                                src="{{ asset('img/gallery/10.png') }}" alt="9">
                        </div>
                    </div>
                    <div class="grid gap-4">
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/1.png') }}" alt="10">
                        </div>
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/5.png') }}" alt="11">
                        </div>
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('img/gallery/9.png') }}" alt="12">
                        </div>
                    </div>
                </div>

            </div>

            <!-- CTA Section -->
            <div class="bg-gradient-to-r from-pink-500 to-pink-600 rounded-3xl p-12 text-center text-white">
                <h2 class="text-3xl font-bold mb-4">Ready to Taste the Joy ?</h2>
                <p class="text-xl text-pink-100 mb-8 max-w-2xl mx-auto">
                    Yuk, rasakan serunya makanan tradisional versi kekinian bersama CeriaEats!
                </p>
                <a href="{{ route('user.shop.index') }}"
                    class="inline-block bg-yellow-400 text-white hover:bg-pink-500 px-8 py-4 rounded-full font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                    Order Now
                </a>
            </div>
        </div>
    </section>
@endsection
