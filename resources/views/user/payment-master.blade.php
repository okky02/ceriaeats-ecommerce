@extends('layouts.user')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-pink-50 to-purple-50 py-8 px-4 lg:py-12">
        <!-- Judul -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-pink-600 mb-2">Payment</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Selesaikan pembayaran Anda untuk memproses pesanan. Harap isi
                semua informasi yang diperlukan dengan akurat.</p>
        </div>

        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Order Summary -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden sticky top-[115px] transition-all duration-300 hover:shadow-xl">
                    <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-receipt mr-2"></i>
                            Order Summary
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4 text-gray-700">
                            <div class="flex justify-between pb-3 border-b border-gray-100">
                                <span class="font-medium text-gray-600 flex items-center">
                                    <i class="fas fa-hashtag mr-2 text-pink-500"></i>
                                    ORDER #
                                </span>
                                <span class="font-semibold">{{ $order->order_number }}</span>
                            </div>

                            <div class="pb-3 border-b border-gray-100">
                                <div class="font-medium text-gray-600 flex items-center mb-3">
                                    <i class="fas fa-shopping-bag mr-2 text-pink-500"></i>
                                    Products
                                </div>
                                <div class="space-y-4 pl-2">
                                    @foreach ($order->items as $item)
                                        <div class="flex justify-between items-start gap-3">
                                            <div class="flex items-start space-x-3">
                                                <!-- Product Image -->
                                                <div
                                                    class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden border border-gray-200">
                                                    <img src="{{ asset('storage/' . $item->product->gambar) }}"
                                                        alt="{{ $item->product->nama_produk }}"
                                                        class="w-full h-full object-cover">
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $item->product->nama_produk }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                                </div>
                                            </div>
                                            <span class="font-medium whitespace-nowrap">
                                                Rp{{ number_format($item->product->harga * $item->quantity, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-between pb-2 border-b border-gray-100">
                                <span class="font-medium text-gray-600 flex items-center">
                                    <i class="fas fa-tag mr-2 text-pink-500"></i>
                                    Subtotal
                                </span>
                                <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>

                            @if ($order->voucher_code)
                                <div class="flex justify-between pb-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600 flex items-center">
                                        <i class="fas fa-ticket-alt mr-2 text-yellow-500"></i>
                                        Voucher Code
                                    </span>
                                    <span>{{ $order->voucher_code }}</span>
                                </div>
                                <div class="flex justify-between pb-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Discount
                                        ({{ $order->discount_percentage }}%)</span>
                                    <span
                                        class="text-red-500">-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between pt-4">
                                <span class="font-bold text-lg text-gray-700 flex items-center">
                                    <i class="fas fa-money-bill-wave mr-2 text-pink-500"></i>
                                    Total Amount
                                </span>
                                <span
                                    class="font-bold text-lg text-pink-600">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-yellow-800 mb-1">Important Notice</h4>
                                    <p class="text-sm text-gray-700">Please complete your payment within 24 hours to avoid
                                        order cancellation. Upload clear payment proof for faster verification.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-500 via-pink-600 to-pink-500 px-6 py-4">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-credit-card mr-2"></i>
                            Payment Confirmation
                        </h3>
                    </div>

                    <div class="p-6">
                        <!-- User Info -->
                        <div
                            class="mb-8 bg-gradient-to-r from-pink-50 to-yellow-50 rounded-xl p-5 border border-pink-200 shadow-sm">
                            <div class="flex items-center mb-3">
                                <span class="bg-pink-100 p-2 rounded-full mr-3 text-pink-600">
                                    <i class="fas fa-user-circle"></i>
                                </span>
                                <h3 class="text-lg font-semibold text-pink-700">Your Information</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                                <div class="bg-white p-3 rounded-lg shadow-xs">
                                    <p class="font-medium text-gray-600 flex items-center">
                                        <i class="fas fa-user mr-2 text-pink-500"></i>
                                        Name:
                                    </p>
                                    <p class="mt-1">{{ $user->name }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow-xs">
                                    <p class="font-medium text-gray-600 flex items-center">
                                        <i class="fas fa-envelope mr-2 text-pink-500"></i>
                                        Email:
                                    </p>
                                    <p class="mt-1">{{ $user->email }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow-xs">
                                    <p class="font-medium text-gray-600 flex items-center">
                                        <i class="fas fa-phone mr-2 text-pink-500"></i>
                                        Phone:
                                    </p>
                                    <p class="mt-1">{{ $user->phone ?? 'Not provided' }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow-xs">
                                    <p class="font-medium text-gray-600 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-pink-500"></i>
                                        Address:
                                    </p>
                                    <p class="mt-1">{{ $user->address ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>

                        <form x-data="{ loading: false }" @submit="loading = true"
                            action="{{ route('user.payment.confirm', $order->id) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <!-- Personal Information Section -->
                            <div class="space-y-6 bg-pink-50 rounded-xl p-5 border border-pink-100">
                                <h4 class="text-lg font-semibold text-pink-700 flex items-center border-b pb-3">
                                    <i class="fas fa-user-edit mr-2"></i>
                                    Personal Details
                                </h4>

                                <!-- Name -->
                                <div>
                                    <label for="name" class="block mb-2 font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-signature mr-2 text-pink-500"></i>
                                        Full Name
                                    </label>
                                    <input id="name" name="name" type="text"
                                        value="{{ old('name', $user->name) }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200"
                                        required autofocus autocomplete="name" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block mb-2 font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-at mr-2 text-pink-500"></i>
                                        Email Address
                                    </label>
                                    <input id="email" name="email" type="email"
                                        value="{{ old('email', $user->email) }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200"
                                        required autocomplete="email" />
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block mb-2 font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-mobile-alt mr-2 text-pink-500"></i>
                                        Phone Number
                                    </label>
                                    <input id="phone" name="phone" type="tel" required
                                        value="{{ old('phone', $user->phone) }}"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200" />
                                </div>

                                <!-- Address -->
                                <div>
                                    <label for="address" class="block mb-2 font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-home mr-2 text-pink-500"></i>
                                        Complete Address
                                    </label>
                                    <textarea id="address" name="address" required rows="3"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200">{{ old('address', $user->address) }}</textarea>
                                </div>
                            </div>

                            <!-- Payment Method Section -->
                            <div class="space-y-6 bg-pink-50 rounded-xl p-5 border border-pink-100">
                                <h4 class="text-lg font-semibold text-pink-700 flex items-center border-b pb-3">
                                    <i class="fas fa-wallet mr-2"></i>
                                    Payment Method
                                </h4>

                                <div x-data="paymentDropdown()">
                                    <!-- Toast Notification -->
                                    <div x-show="showToast" x-transition:enter="transition ease-out duration-500"
                                        x-transition:enter-start="opacity-0 -translate-y-5"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-300"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-5"
                                        class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2"
                                        x-cloak>
                                        <i class="fas fa-check-circle"></i>
                                        <span x-text="toastMessage"></span>
                                    </div>


                                    <!-- Payment Method Dropdown -->
                                    <div class="relative">
                                        <button @click="open = !open" type="button"
                                            class="w-full flex justify-between items-center px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm text-left focus:outline-none focus:ring-2 focus:ring-pink-500 hover:bg-pink-50 transition-all duration-200">
                                            <div class="flex items-center">
                                                <template x-if="selected">
                                                    <div class="flex items-center space-x-3">
                                                        <img :src="selected.image" class="w-8 h-8 object-contain" />
                                                        <span x-text="selected.bank" class="font-medium"></span>
                                                    </div>
                                                </template>
                                                <template x-if="!selected">
                                                    <span class="text-gray-400 flex items-center">
                                                        <i class="fas fa-chevron-circle-down mr-2 text-pink-500"></i>
                                                        Select a payment method
                                                    </span>
                                                </template>
                                            </div>
                                            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-200"
                                                :class="{ 'transform rotate-180': open }"></i>
                                        </button>

                                        <!-- Dropdown Options -->
                                        <ul x-show="open" @click.outside="open = false"
                                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                            @foreach ($paymentMethods as $method)
                                                <li @click="selectBank({
                                                    id: {{ $method->id }},
                                                    bank: '{{ strtoupper($method->bank) }}',
                                                    image: '{{ asset('storage/' . $method->gambar) }}',
                                                    rekening: '{{ $method->no_rekening }}',
                                                    nama: '{{ $method->nama }}'
                                                })"
                                                    class="px-4 py-3 hover:bg-pink-50 cursor-pointer flex items-center space-x-3 border-b border-gray-100 last:border-b-0 transition-colors duration-150">
                                                    <img src="{{ asset('storage/' . $method->gambar) }}"
                                                        class="w-8 h-8 object-contain" />
                                                    <div>
                                                        <p class="font-medium">{{ strtoupper($method->bank) }}</p>
                                                        <p class="text-xs text-gray-500">{{ $method->nama }}</p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <!-- Selected Payment Details -->
                                    <div x-show="selected" x-transition
                                        class="mt-4 p-4 bg-white border border-pink-200 rounded-lg space-y-3 shadow-sm">
                                        <h5 class="font-semibold text-pink-700 flex items-center">
                                            <i class="fas fa-university mr-2"></i>
                                            Payment Instructions
                                        </h5>
                                        <div class="space-y-2 text-sm">
                                            <p class="flex items-center bg-pink-50 p-2 rounded">
                                                <span class="w-24 font-medium text-gray-600 flex items-center">
                                                    <i class="fas fa-landmark mr-2 text-pink-500"></i>
                                                    Bank:
                                                </span>
                                                <span x-text="selected?.bank || ''" class="font-semibold text-pink-600"></span>
                                            </p>
                                            <p class="flex items-center bg-pink-50 p-2 rounded">
                                                <span class="w-24 font-medium text-gray-600 flex items-center">
                                                    <i class="fas fa-hashtag mr-2 text-pink-500"></i>
                                                    Account No:
                                                </span>
                                                <span x-text="selected?.rekening || ''" class="font-mono font-semibold"></span>
                                                <button @click="copyToClipboard(selected.rekening)"
                                                    class="ml-2 text-pink-600 hover:text-pink-800 transition-colors"
                                                    title="Copy to clipboard">
                                                    <i class="far fa-copy"></i>
                                                </button>
                                            </p>
                                            <p class="flex items-center bg-pink-50 p-2 rounded">
                                                <span class="w-24 font-medium text-gray-600 flex items-center">
                                                    <i class="fas fa-user-tie mr-2 text-pink-500"></i>
                                                    Account Name:
                                                </span>
                                                <span x-text="selected?.nama || ''" class="font-semibold"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <input type="hidden" name="payment_method_id" :value="selected?.id">
                                </div>
                            </div>

                            <!-- Proof of Payment Section -->
                            <div class="space-y-6 bg-pink-50 rounded-xl p-5 border border-pink-100">
                                <h4 class="text-lg font-semibold text-pink-700 flex items-center border-b pb-3">
                                    <i class="fas fa-file-invoice-dollar mr-2"></i>
                                    Payment Proof
                                </h4>

                                <!-- Preview Bukti Pembayaran -->
                                <div id="preview-container"
                                    class="flex flex-col items-center w-40 h-40 rounded-lg border border-pink-300 mb-4 hidden relative">
                                    <img id="proof-preview" src="#" alt="Proof Preview"
                                        class="w-full h-full object-cover rounded-lg p-1">
                                    <span id="file-name"
                                        class="absolute bottom-0 bg-white bg-opacity-80 text-xs text-center text-pink-700 px-1 py-0.5 rounded-b-md w-full truncate">
                                    </span>
                                </div>


                                <div class="flex items-center justify-center w-full">
                                    <label for="proof"
                                        class="flex flex-col w-full h-32 border-2 border-dashed border-pink-300 rounded-lg hover:bg-pink-100 transition duration-300 cursor-pointer relative">
                                        <div class="flex flex-col items-center justify-center pt-7 pointer-events-none">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-pink-400 mb-2"></i>
                                            <p class="text-sm text-pink-500">Click to upload</p>
                                        </div>
                                        <input id="proof" name="proof" type="file" accept="image/*" required
                                            class="absolute inset-0 opacity-0 cursor-pointer z-10"
                                            onchange="previewProof(event)" />
                                    </label>
                                </div>


                                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-circle text-yellow-500 mt-1 mr-3"></i>
                                        <div>
                                            <h4 class="font-semibold text-yellow-800 mb-1">Important</h4>
                                            <p class="text-sm text-gray-700">Please ensure your payment proof is clear and
                                                shows:</p>
                                            <ul class="text-sm text-gray-700 mt-1 list-disc list-inside">
                                                <li>Bank name</li>
                                                <li>Account number</li>
                                                <li>Transfer amount</li>
                                                <li>Transaction date/time</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div class="space-y-6 bg-pink-50 rounded-xl p-5 border border-pink-100">
                                <label for="notes"
                                    class="text-lg font-semibold text-pink-700 flex items-center border-b pb-3">
                                    <i class="fas fa-sticky-note mr-2 "></i>
                                    Notes
                                </label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all duration-200"
                                    placeholder="Tambahkan catatan untuk admin, jika ada...">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <x-spinner-button loading="loading" label="Confirm Payment" loadingLabel="Processing..."
                                    bgColor="bg-pink-500"
                                    hoverColor="hover:shadow-lg hover:-translate-y-0.5 hover:bg-pink-600"
                                    ringColor="focus:ring-pink-500 focus:ring-opacity-50" textSize="text-base font-bold"
                                    padding="py-4 px-6" rounded="rounded-xl" fullWidth="true"
                                    icon='<i class="fas fa-paper-plane mr-2 transition-transform group-hover:scale-110"></i>' />

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Payment Method Dropdown
        function paymentDropdown() {
            return {
                open: false,
                selected: null,
                showToast: false,
                toastMessage: '',
                selectBank(data) {
                    this.selected = data;
                    this.open = false;
                },
                copyToClipboard(text) {
                    navigator.clipboard.writeText(text).then(() => {
                        this.toastMessage = 'Account number copied to clipboard!';
                        this.showToast = true;
                        setTimeout(() => this.showToast = false, 3000);
                    });
                }
            }
        }

        // Priview Bukti Pembayaran
        function previewProof(event) {
            const input = event.target;
            const preview = document.getElementById('proof-preview');
            const container = document.getElementById('preview-container');
            const fileNameText = document.getElementById('file-name');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                    fileNameText.textContent = file.name;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
