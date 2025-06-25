<footer class="bg-gradient-to-r from-pink-100/60 via-pink-200/60 to-pink-100/60 text-pink-500">
    <div class="container mx-auto w-full max-w-screen-xl py-6 lg:py-12  px-4 md:px-0">
        <!-- Main Footer Content -->
        <div class="flex flex-col md:flex-row justify-between items-start gap-8 mb-8">
            <!-- Brand (Kiri) -->
            <div class="w-full md:w-1/3">
                <div class="flex items-center justify-start mb-4">
                    <img src="{{ asset('img/Logo-Ceria-2.png') }}" class="h-12 mr-3" alt="CeriaEats Logo" loading="lazy" />
                    <span class="text-2xl font-semibold">CeriaEats.</span>
                </div>
                <p class="text-sm text-left">
                    Delicious food delivered to your doorstep with a smile.
                </p>
            </div>

            <!-- Quick Links & Contact (Kanan) -->
            <div class="w-full md:w-2/3 flex flex-col md:flex-row justify-end gap-8 gap-y-6 text-left">
                <!-- Quick Links -->
                <div class="min-w-[200px]">
                    <h3 class="text-lg font-medium text-pink-600 uppercase mb-4">Quick Links</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('user.home.index') }}"
                                class="flex justify-start items-center transition-colors 
                                {{ request()->is('user/home') ? 'text-yellow-400' : 'hover:text-yellow-400 text-pink-500' }}">
                                <i data-lucide="move-right" class="mr-2 w-4 h-4"></i> Home
                            </a>

                        </li>
                        <li>
                            <a href="{{ route('user.shop.index') }}"
                                class="flex justify-start items-center transition-colors 
                                {{ request()->is('user/shop') ? 'text-yellow-400' : 'hover:text-yellow-400 text-pink-500' }}">
                                <i data-lucide="move-right" class="mr-2 w-4 h-4"></i> Shop
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.order.index') }}"
                                class="flex justify-start items-center transition-colors 
                                {{ request()->is('user/order') ? 'text-yellow-400' : 'hover:text-yellow-400 text-pink-500' }}">
                                <i data-lucide="move-right" class="mr-2 w-4 h-4"></i> My Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.about-master') }}"
                                class="flex justify-start items-center transition-colors 
   {{ request()->routeIs('user.about-master') ? 'text-yellow-400' : 'hover:text-yellow-400 text-pink-500' }}">
                                <i data-lucide="move-right" class="mr-2 w-4 h-4"></i> About
                            </a>
                        </li>
                        <li>
                            <a href=""
                                class="flex justify-start items-center transition-colors">
                                <i data-lucide="move-right" class="mr-2 w-4 h-4"></i> Contact
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="min-w-[200px]">
                    <h3 class="text-lg font-medium text-pink-600 uppercase mb-4">Contact Us</h3>
                    <ul class="space-y-3">
                        <li class="flex justify-start items-start">
                            <i data-lucide="map-pin" class="mr-2 mt-0.5 w-4 h-4 flex-shrink-0"></i>
                            <address class="not-italic">
                                <a class="hover:text-yellow-400 transition-colors"
                                    href="https://www.google.com/maps?q=Bekasi+Timur+Regensi,+Jl.+Murai+8,+Blok+H+19+no+49,+Kota+Bekasi,+Jawa+Barat+17151">
                                    Bekasi Timur Regensi, <br /> Jl. Murai 8, Blok H 19 no 49,<br />
                                    Kota Bekasi, Jawa Barat 17151
                                </a>
                            </address>
                        </li>
                        <li class="flex justify-start items-center">
                            <i data-lucide="phone" class="mr-2 w-4 h-4"></i>
                            <a href="tel:+6281295264742"
                                class="hover:text-yellow-400 transition-colors">0812-9526-4742</a>
                        </li>
                        <li class="flex justify-start items-center">
                            <i data-lucide="mail" class="mr-2 w-4 h-4"></i>
                            <a href="mailto:admin@ceriaeats.com"
                                class="hover:text-yellow-400 transition-colors">admin@ceriaeats.com</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <hr class="border-pink-300 my-6" />

        <!-- Footer Bottom -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm text-center md:text-left">
                © 2025 <a href="#" class="hover:underline">CeriaEats</a>. All Rights Reserved.
            </p>
            <div class="flex flex-wrap justify-center gap-3">
                <img src="{{ asset('img/payments/bca.png') }}" class="h-9 w-auto" alt="bca" loading="lazy" />
                <img src="{{ asset('img/payments/bni.png') }}" class="h-9 w-auto"alt="bni" loading="lazy" />
                <img src="{{ asset('img/payments/mandiri.png') }}" class="h-9 w-auto" alt="mandiri" loading="lazy" />
                <img src="{{ asset('img/payments/bri.png') }}" class="h-9 w-auto" alt="bri" loading="lazy" />
            </div>
        </div>
    </div>
</footer>
