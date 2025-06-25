@extends('layouts.user')

@section('content')
<section class="flex-grow flex flex-col bg-pink-50">
    <div class="mx-auto w-full max-w-screen-xl py-10 px-4 lg:px-6">
            @if (session('success'))
                <x-success-toast title="Success!!" message="{{ session('success') }}" />
            @endif

            @if (session('error_title'))
                <x-error-toast title="{{ session('error_title') }}" message="{{ session('error_message') }}" />
            @endif

            <!-- Heading -->
            <div class="mb-10 text-center">
                <h2 class="text-4xl font-extrabold text-pink-600">Cart</h2>
                <p class="mt-2 text-lg text-gray-600">Periksa dulu barang belanjaan kamu sebelum checkout</p>
            </div>

            <!-- Flex container -->
            <div class="flex flex-col lg:flex-row items-start gap-8">

                <!-- Cart Table -->
                <div class="p-6 w-full lg:w-3/4  overflow-x-auto rounded-2xl border border-gray-100 bg-white shadow-xl">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-center border-b border-gray-200 text-pink-600 uppercase tracking-wider">
                                <th class="px-2 py-3">Image</th>
                                <th class="px-2 py-3">Product</th>
                                <th class="px-2 py-3">Price</th>
                                <th class="px-2 py-3">Qty</th>
                                <th class="px-2 py-3">Subtotal</th>
                                <th class="text-white px-2 py-3">action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cart?->items ?? [] as $item)
                                <tr class="text-center border-b border-gray-100 hover:bg-pink-50 transition">
                                    <td class="px-2 py-4 flex justify-center items-center">
                                        <img src="{{ asset('storage/' . ($item->product->gambar ?? 'default.png')) }}"
                                            class="w-20 h-20 rounded-md object-cover" />
                                    </td>
                                    <td class="px-2 py-4 text-gray-700 font-semibold">{{ $item->product->nama_produk }}</td>
                                    <td class="px-2 py-4 text-gray-700 font-semibold">
                                        Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-2 py-4">
                                        <div class="flex justify-center items-center h-full">
                                            <div
                                                class="bg-gray-100 rounded-md flex items-center justify-between px-3 py-1 w-[100px] h-10">
                                                <button type="button" class="text-pink-500 hover:text-pink-700"
                                                    onclick="decreaseQuantity(this)" data-item-id="{{ $item->id }}">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <span
                                                    class="text-gray-800 font-semibold text-sm quantity">{{ $item->quantity }}</span>
                                                <button type="button" class="text-pink-500 hover:text-pink-700"
                                                    onclick="increaseQuantity(this)" data-item-id="{{ $item->id }}">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 py-4 text-gray-700 font-semibold subtotal">
                                        Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <form action="{{ route('user.cart.remove', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus item ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="openDeleteCartModal('{{ route('user.cart.remove', $item->id) }}')"
                                                class="text-red-500 hover:text-red-700 text-lg">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center pt-10 text-gray-400">
                                        <i class="fa-solid fa-box-open fa-xl mb-2 block"></i>
                                        Cart is empty.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Cart Summary -->
                <div class="lg:w-1/4 bg-white rounded-2xl shadow-md p-6 sticky top-[115px] ">
                    <h2 class="text-pink-600 mb-6 text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-receipt"></i> Order Summary
                    </h2>

                    <div class="space-y-3 text-gray-600 text-sm">
                        <div class="flex justify-between">
                            <span>Subtotal (<span class="order-summary-item-count">{{ $cart->items->count() }}
                                    items</span>)</span>
                            <span class="order-summary-subtotal">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-pink-500 font-medium">
                            <span class="discount-label">
                                Discount{{ $cart->discount_percentage > 0 ? " ({$cart->discount_percentage}%)" : '' }}
                            </span>
                            <span class="discount order-summary-discount">
                                -Rp{{ number_format($cart->discount_amount ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-gray-800">
                            <span>Total</span>
                            <span
                                class="total order-summary-total">Rp{{ number_format($subtotal - $cart->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <form x-data="{ loading: false }" @submit="loading = true" action="{{ route('user.checkout') }}"
                            method="POST">
                            @csrf
                            <x-spinner-button loading="loading" label="Proceed to Checkout" loadingLabel="Processing..."
                                bgColor="bg-pink-500" hoverColor="hover:bg-yellow-400" ringColor="focus:ring-yellow-400"
                                textSize="text-base" padding="px-6 py-3" rounded="rounded-full" fullWidth="true"
                                icon='<i class="fas fa-credit-card mr-2"></i>' />

                        </form>
                        <button onclick="window.location.href='{{ route('user.shop.index') }}'"
                            class="w-full text-pink-600 px-6 py-3 bg-white border border-pink-500 hover:border-yellow-400 hover:bg-yellow-50 hover:text-yellow-400 rounded-full text-base font-semibold transition flex items-center justify-center">
                            <i class="fas fa-shopping-bag mr-2"></i> Continue Shopping
                        </button>
                    </div>

                    <div class="mt-6 p-4 bg-pink-50 rounded-lg">
                        <div class="flex items-center gap-2 text-pink-600 mb-2">
                            <i class="fas fa-tag"></i>
                            <span class="font-medium">Voucher Code</span>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" id="voucher-code-input" placeholder="Enter code"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-full text-sm focus:outline-pink-500"
                                value="{{ $cart->discount_percentage > 0 ? $cart->voucher_code : '' }}">
                            <button id="apply-voucher-btn"
                                class="flex-shrink-0 px-3 py-2 bg-pink-500 text-white rounded-full text-sm font-medium hover:bg-yellow-400">
                                Apply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="toast-container" class="fixed top-5 right-5 z-50 space-y-3"></div>
    </section>

    <!-- Modal Delete Cart Item -->
    <div id="modal-delete-payment"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-sm space-y-4 shadow-xl animate-fade-in text-center">
            <div class="text-red-600 text-3xl">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Hapus Produk dari Keranjang?</h3>
            <p class="text-sm text-gray-600">Apakah kamu yakin ingin menghapus produk ini dari keranjang?</p>
            <div class="flex justify-center mt-6 space-x-3">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-300">
                    Batal
                </button>
                <form id="deleteForm" x-data="{ loading: false }" @submit="loading = true" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <x-spinner-button loading="loading" label="Hapus" loadingLabel="Menghapus..." bgColor="bg-red-600"
                        hoverColor="hover:bg-red-700" ringColor="focus:ring-red-500"
                        icon='<i class="fa-solid fa-trash-alt mr-2"></i>' />
                </form>
            </div>
        </div>
    </div>

    <style>
        .toast {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            animation: slide-in 0.3s ease, fade-out 0.5s ease 3s forwards;
            color: white;
        }

        .toast-success {
            background-color: #10b981;
            /* green */
        }

        .toast-error {
            background-color: #ef4444;
            /* red */
        }

        .toast-info {
            background-color: #3b82f6;
            /* blue */
        }

        .toast-title {
            font-weight: bold;
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fade-out {
            to {
                opacity: 0;
                transform: translateX(50px);
            }
        }
    </style>

    <script>
        // Delete Modal
        function openDeleteCartModal(actionUrl) {
            const modal = document.getElementById('modal-delete-payment');
            const form = document.getElementById('deleteForm');
            form.action = actionUrl;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('modal-delete-payment');
            modal.classList.add('hidden');
        }

        // Voucher
        document.getElementById('apply-voucher-btn').addEventListener('click', function() {
            const code = document.getElementById('voucher-code-input').value.trim();

            // Kalau voucher kosong, anggap reset diskon
            if (code === '') {
                // Kirim request reset ke server
                fetch('{{ route('user.reset-voucher') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            document.querySelector('.discount').textContent = '-Rp0';
                            document.querySelector('.discount-label').textContent = 'Discount';
                            document.getElementById('voucher-code-input').value = '';

                            const subtotalText = document.querySelector('.order-summary-subtotal').textContent;
                            const subtotal = parseInt(subtotalText.replace(/[^\d]/g, ''));
                            document.querySelector('.total').textContent = 'Rp' + subtotal.toLocaleString(
                                'id-ID');

                            showToast('success', 'Voucher direset', 'Diskon dihapus.');
                        }
                    });
                return;
            }

            // Kalau voucher diisi, kirim ke server
            fetch('{{ route('user.apply-voucher') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        voucher_code: code
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.querySelector('.discount').textContent = '-Rp' + data.discount_amount;
                        document.querySelector('.total').textContent = 'Rp' + data.total;
                        document.querySelector('.discount-label').textContent =
                            `Discount (${data.discount_percentage}%)`;
                        showToast('success', 'Voucher berhasil!', 'Diskon telah diterapkan.');
                    } else {
                        alert(data.message);
                    }
                });
        });

        // Toast Voucher
        function showToast(type, title, message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                                <div>
                                    <div class="toast-title">${title}</div>
                                    <div>${message}</div>
                                </div>
                                `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 4000);
        }
    </script>
@endsection
