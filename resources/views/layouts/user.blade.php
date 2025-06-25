<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('img/Logo-Ceria-2.png') }}" type="image/png">
    <title>{{ config('app.name', 'CeriaEats') }}</title>

    <!-- js, css -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-white text-gray-800  min-h-screen flex flex-col">

    @include('user.home-partials.navbar')

    <iframe name="downloadFrame" style="display: none;"></iframe>

    <main  class="flex-grow flex flex-col">
        @yield('content')
    </main>

    @include('user.home-partials.footer')

    <!-- Back to Top Button -->
    <a href="#" id="backToTop"
        class="opacity-0 scale-90 pointer-events-none fixed bottom-5 right-5 z-50 w-12 h-12 rounded-full bg-pink-500 hover:bg-yellow-500 flex items-center justify-center text-white shadow-lg transition-all duration-300 ease-in-out">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Loading Spinner -->
    <div id="pageLoader"
        class="fixed inset-0 bg-pink-300 bg-opacity-80 flex flex-col items-center justify-center z-50 hidden ">
        <div class="relative w-28 h-28 mb-4">
            <div class="absolute inset-0 border-4 border-pink-500 border-t-transparent rounded-full animate-spin">
            </div>
            <img src="{{ asset('img/Logo-Ceria-2.png') }}" alt="Logo"
                class="absolute inset-0 m-auto w-20 h-20 object-contain">
        </div>
    </div>

    <script>
        // Scroll
        const backToTop = document.getElementById('backToTop');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 200) {
                backToTop.classList.remove('opacity-0', 'scale-90', 'pointer-events-none');
                backToTop.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
            } else {
                backToTop.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
                backToTop.classList.add('opacity-0', 'scale-90', 'pointer-events-none');
            }
        });

        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Loading
        document.addEventListener('click', function(e) {
            const target = e.target;

            const link = target.closest('a[href]');
            if (link) {
                const href = link.getAttribute('href');

                if (
                    link.target === '_blank' ||
                    href.startsWith('javascript:') ||
                    href.startsWith('#') ||
                    href.startsWith('mailto:') ||
                    href.startsWith('tel:')
                ) {
                    return;
                }

                document.getElementById('pageLoader')?.classList.remove('hidden');
                return;
            }

            const clickable = target.closest('[onclick]');
            const onclick = clickable?.getAttribute('onclick');
            if (onclick && onclick.includes('window.location')) {
                document.getElementById('pageLoader')?.classList.remove('hidden');
            }
        });

        // Quantity Home, Shop, Product & Cart
        function increaseQuantity(button) {
            const qtySpan = button.parentElement.querySelector('.quantity');
            let qty = parseInt(qtySpan.textContent);
            qty++;
            qtySpan.textContent = qty;

            const form = button.closest('form');
            const input = form ? form.querySelector('.shop-cart-qty-input') : null;

            if (input) {
                // Halaman shop/home/detail
                input.value = qty;
            } else {
                // Halaman cart (pakai AJAX)
                const itemId = button.getAttribute('data-item-id');
                if (itemId) updateQuantityAjax(itemId, qty, qtySpan);
            }

            // Tambahan supaya kalau form-nya tidak satu kontainer, tetap sync
            syncQtyToHidden(button, qty);
        }

        function decreaseQuantity(button) {
            const qtySpan = button.parentElement.querySelector('.quantity');
            let qty = parseInt(qtySpan.textContent);
            if (qty > 1) qty--;
            qtySpan.textContent = qty;

            const form = button.closest('form');
            const input = form ? form.querySelector('.shop-cart-qty-input') : null;

            if (input) {
                input.value = qty;
            } else {
                const itemId = button.getAttribute('data-item-id');
                if (itemId) updateQuantityAjax(itemId, qty, qtySpan);
            }

            syncQtyToHidden(button, qty);
        }

        // Fungsi tambahan untuk jaga-jaga kalau input hidden ada di tempat berbeda
        function syncQtyToHidden(button, qty) {
            const container = button.closest('.mt-6') || document;
            container.querySelectorAll('.shop-cart-qty-input').forEach(input => {
                input.value = qty;
            });
        }

        function updateQuantityAjax(itemId, newQty, qtySpan) {
            fetch(`/user/cart-item/${itemId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        quantity: newQty
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        const subtotalCell = qtySpan.closest('tr').querySelector('.subtotal');
                        if (subtotalCell) {
                            subtotalCell.textContent = 'Rp' + data.subtotal;
                        }

                        // ✅ Update jumlah item unik
                        const itemCountSpan = document.querySelector('.order-summary-item-count');
                        if (itemCountSpan) {
                            itemCountSpan.textContent = `${data.uniqueItemCount} items`;
                        }

                        // ✅ Update cart subtotal
                        const orderSubtotal = document.querySelector('.order-summary-subtotal');
                        if (orderSubtotal) {
                            orderSubtotal.textContent = 'Rp' + data.cartSubtotal;
                        }

                        // ✅ Update discount
                        const discount = document.querySelector('.order-summary-discount');
                        if (discount) {
                            discount.textContent = '-Rp' + data.discountAmount;
                        }

                        // ✅ Update total
                        const total = document.querySelector('.order-summary-total');
                        if (total) {
                            total.textContent = 'Rp' + data.totalAmount;
                        }
                    } else {
                        alert('Gagal update: ' + data.message);
                    }
                });
        }

        // Tetap dipertahankan, just in case ada kondisi submit form manual
        document.addEventListener('submit', function(e) {
            if (e.target.matches('form[action*="cart.add"]')) {
                const parent = e.target.closest('.mt-6') || document;
                const qtySpan = parent.querySelector('.quantity');
                const input = e.target.querySelector('.shop-cart-qty-input');
                if (qtySpan && input) {
                    input.value = qtySpan.textContent.trim();
                }
            }
        });
    </script>
</body>

</html>
