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
            <!-- Judul -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-pink-600">My Orders</h2>
                <p class="text-lg text-gray-500 mt-3">Periksa riwayat pembelian dan status pembayaran Anda</p>
            </div>

            <!-- Card Container -->
            <div class="p-6 w-full overflow-x-auto rounded-2xl border border-gray-100 bg-white shadow-xl">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-center border-b border-gray-200 text-pink-600 uppercase tracking-wider">
                            <th class="text-left px-2 py-3">Order #</th>
                            <th class="text-left px-2 py-3">Date</th>
                            <th class="text-left px-2 py-3">Product</th>
                            <th class="px-2 py-3">Total</th>
                            <th class="px-2 py-3">Status</th>
                            <th class="px-2 py-3">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-pink-50 transition-colors duration-200">
                                <!-- Order Number -->
                                <td class="px-3 py-4 text-left font-medium text-gray-900">
                                    #{{ $order->order_number }}
                                </td>

                                <!-- Date -->
                                <td class="px-3 py-4 text-left text-gray-600">
                                    {{ $order->created_at->format('Y-m-d') }}
                                </td>

                                <!-- Products -->
                                <td class="px-3 py-4 text-left">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($order->items as $item)
                                            <span class="text-sm bg-gray-100 rounded-full px-2.5 py-1 text-gray-800">
                                                {{ $item->product->nama_produk }} ×{{ $item->quantity }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <!-- Total -->
                                <td class="px-3 py-4 font-semibold text-gray-900">
                                    <div class="flex justify-center space-x-2">
                                        Rp{{ number_format($order->total, 0, ',', '.') }}
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-3 py-4">
                                    <div class="flex justify-center space-x-2">
                                        @if ($order->status == 'unpaid')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 animate-pulse">
                                                <i class="fa-solid fa-circle-dollar-to-slot mr-1"></i>Unpaid
                                            </span>
                                        @elseif($order->status == 'waiting_confirmation')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                <i class="fa-solid fa-spinner mr-1 animate-spin"></i> Process
                                            </span>
                                        @elseif($order->status == 'approved')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <span class="relative flex items-center justify-center w-5 h-5">
                                                    <span
                                                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                                    <i class="fa-solid fa-check-circle text-green-600 relative z-10"></i>
                                                </span>
                                                Approved
                                            </span>
                                        @elseif($order->status == 'denied')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                <span class="relative flex items-center justify-center w-5 h-5">
                                                    <span
                                                        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                                    <i class="fa-solid fa-times-circle text-red-600 relative z-10"></i>
                                                </span>
                                                Denied
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-3 py-4">
                                    <div class="flex justify-center space-x-2">
                                        @if ($order->status == 'unpaid')
                                            <a href="{{ route('user.payment-master', $order->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-semibold text-white bg-green-500 hover:bg-green-600 transition-colors">
                                                <i class="fa-solid fa-money-check-dollar mr-1.5"></i> Pay
                                            </a>
                                        @else
                                            <button onclick="openModal({{ $order->id }})"
                                                class="inline-flex items-center justify-center p-2 rounded-full text-pink-500 hover:bg-pink-100 transition-colors"
                                                title="View Details">
                                                <i class="fa-solid fa-eye text-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center pt-10 text-gray-400">
                                    <i class="fa-solid fa-box-open fa-xl mb-2 block"></i>
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @foreach ($orders as $order)
        <!-- Modal -->
        <div id="orderModal{{ $order->id }}"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
            <div
                class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl relative max-h-[90vh] overflow-hidden animate-fade-in">

                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-pink-50 to-white z-10 p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_number }}</h3>
                            <div class="flex items-center mt-1 space-x-2">
                                @if ($order->status == 'unpaid')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fa-regular fa-clock mr-1"></i> Unpaid
                                    </span>
                                @elseif ($order->status == 'waiting_confirmation')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        <i class="fa-solid fa-spinner mr-1 animate-spin"></i> Process
                                    </span>
                                @elseif ($order->status == 'approved')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        <span class="relative flex items-center justify-center w-5 h-5">
                                            <span
                                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
                                            <i class="fa-solid fa-check-circle text-green-600 relative z-10"></i>
                                        </span>
                                        Approved
                                    </span>
                                @elseif ($order->status == 'denied')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <span class="relative flex items-center justify-center w-5 h-5">
                                            <span
                                                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                            <i class="fa-solid fa-times-circle text-red-600 relative z-10"></i>
                                        </span>
                                        Denied
                                    </span>
                                @endif
                                <span class="text-sm text-gray-500">{{ $order->created_at->format('F j, Y') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Amount</p>
                            <p class="text-2xl font-bold text-pink-600">Rp{{ number_format($order->total, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto space-y-6 max-h-[calc(90vh-160px)]">
                    <!-- Payment Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Left: Payment Information -->
                        <div class="bg-gray-50 rounded-xl p-4 md:col-span-2">
                            <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                                <i class="fa-solid fa-receipt mr-2 text-pink-500"></i> Payment Information
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Method</span>
                                    <span class="font-medium text-gray-800 flex items-center">
                                        @if ($order->paymentProof && $order->paymentProof->paymentMethod)
                                            <img src="{{ asset('storage/' . $order->paymentProof->paymentMethod->gambar) }}"
                                                class="h-6 mr-2" alt="Bank Logo">
                                            {{ $order->paymentProof->paymentMethod->bank }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Account Number</span>
                                    <span class="font-medium text-gray-800">
                                        {{ $order->paymentProof->paymentMethod->no_rekening ?? '-' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Account Name</span>
                                    <span class="font-medium text-gray-800">
                                        {{ $order->paymentProof->paymentMethod->nama ?? '-' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Status</span>
                                    <span
                                        class="font-medium {{ $order->status == 'approved' ? 'text-green-600' : ($order->status == 'denied' ? 'text-red-600' : 'text-gray-600') }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Payment Proof Image -->
                        <div
                            class="flex flex-col items-center justify-center border border-pink-200 rounded-xl p-4 bg-white">
                            @if ($order->paymentProof)
                                <img src="{{ asset('storage/' . $order->paymentProof->image) }}" alt="Payment Proof"
                                    class="w-full h-auto rounded-lg shadow-sm cursor-pointer hover:opacity-90 transition"
                                    onclick="window.open('{{ asset('storage/' . $order->paymentProof->image) }}')">
                                <p class="text-xs text-pink-500 mt-2">Click to enlarge</p>
                            @else
                                <div class="text-center p-4">
                                    <i class="fa-solid fa-image text-gray-300 text-3xl mb-2"></i>
                                    <p class="text-sm text-gray-500">No payment proof uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>


                    <!-- Customer Information -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fa-solid fa-user mr-2 text-pink-500"></i> Customer Information
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Name</p>
                                <p class="font-medium">{{ $order->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium">{{ $order->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <p class="font-medium">{{ $order->user->phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Address</p>
                                <p class="font-medium">{{ $order->user->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    @if ($order->paymentProof && $order->paymentProof->notes)
                        <div class="bg-yellow-50 rounded-xl p-4 border-l-4 border-yellow-400">
                            <h4 class="font-medium text-yellow-800  flex items-center">
                                <i class="fa-solid fa-note-sticky mr-2 text-yellow-500"></i> Customer Notes
                            </h4>
                            <p class="text-sm text-yellow-700 whitespace-pre-line">
                                {{ $order->paymentProof->notes }}
                            </p>
                        </div>
                    @endif

                    <!-- Order Items -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fa-solid fa-bag-shopping mr-2 text-pink-500"></i> Order Items
                        </h4>
                        <div class="space-y-4">
                            @foreach ($order->items as $item)
                                <div class="flex items-start border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                                    <div class="flex-shrink-0 h-16 w-16 rounded-lg overflow-hidden bg-gray-100">
                                        <img src="{{ asset('storage/' . $item->product->gambar) }}"
                                            alt="{{ $item->product->nama_produk }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h5 class="font-medium text-gray-800">{{ $item->product->nama_produk }}</h5>
                                        <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">
                                            Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500">Rp{{ number_format($item->price, 0, ',', '.') }}
                                            each</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h4 class="font-medium text-gray-800 mb-3 flex items-center">
                            <i class="fa-solid fa-calculator mr-2 text-pink-500"></i> Order Summary
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if ($order->voucher_code)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Voucher ({{ $order->voucher_code }})</span>
                                    <span
                                        class="font-medium text-green-600">-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}
                                        ({{ $order->discount_percentage }}%)
                                    </span>
                                </div>
                            @endif
                            <div class="flex justify-between pt-2 border-t border-gray-200 mt-2">
                                <span class="text-lg font-bold text-gray-800">Total</span>
                                <span
                                    class="text-lg font-bold text-pink-600">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="sticky bottom-0 bg-white border-t border-gray-100 p-4 flex justify-between items-center">
                    <button onclick="closeModal({{ $order->id }})"
                        class="px-4 py-2 text-white text-sm font-medium bg-red-600 rounded-lg hover:bg-red-700 transition">
                        Close
                    </button>
                    <div class="space-x-2">
                        <form id="pdfDownloadForm{{ $order->id }}"
                            action="{{ route('user.order.exportPdf', $order->id) }}" method="GET"
                            target="downloadFrame" onsubmit="startSpinner({{ $order->id }})">
                            <button type="submit"
                                class="px-4 py-2 bg-pink-600 text-white text-sm font-medium rounded-lg hover:bg-pink-700 transition">
                                <i class="fa-solid fa-download mr-2"></i>Download
                            </button>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function openModal(id) {
            document.getElementById('orderModal' + id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById('orderModal' + id).classList.add('hidden');
        }

        function startSpinner(orderId) {
            const button = document.querySelector(`#pdfDownloadForm${orderId} button`);
            button.disabled = true;
            button.innerHTML = '<i class="fa-solid fa-spinner animate-spin mr-2"></i>Downloading...';

            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = '<i class="fa-solid fa-download mr-2"></i>Download';
            }, 5000);
        }
    </script>
@endsection
