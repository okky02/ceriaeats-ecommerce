@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-pink-100 sm:ml-64 pt-28 pb-12 px-6">
        <div class="max-w-8xl mx-auto space-y-8">

            <!-- Header -->
            <div class="p-6 bg-white shadow-lg rounded-2xl">
                <h2 class="text-2xl font-semibold text-pink-600">
                    <i class="fas fa-shopping-bag mr-2"></i></i>Orders
                </h2>
            </div>

            <!-- Table Order -->
            <div class="overflow-x-auto bg-white p-6 shadow-lg rounded-2xl">
                <!-- Controls -->
                <div class="flex justify-between items-center mb-4">
                    <!-- Show -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-pink-600">Show:</span>
                        <form id="perPageForm" method="GET" action="{{ route('admin.order.index') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <select name="perPage" onchange="document.getElementById('perPageForm').submit()"
                                class="border border-pink-400 rounded-lg px-3 py-2 text-sm focus:ring-pink-500 focus:border-pink-500">
                                <option value="10" {{ $originalPerPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $originalPerPage == 20 ? 'selected' : '' }}>20</option>
                                <option value="30" {{ $originalPerPage == 30 ? 'selected' : '' }}>30</option>
                                <option value="all" {{ $originalPerPage == 'all' ? 'selected' : '' }}>All</option>
                            </select>
                        </form>
                        <span class="text-sm text-pink-600">entries</span>
                    </div>

                    <!-- Search & Sort -->
                    <div class="relative flex items-center space-x-2">
                        <form method="GET" action="{{ route('admin.order.index') }}"
                            class="flex items-center space-x-2 relative">
                            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                            <div class="relative">
                                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                                    class="border rounded-lg px-4 py-2 border-pink-400 pl-10 text-sm focus:ring-pink-500 focus:border-pink-500">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-pink-500"></i>
                            </div>
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

                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="text-pink-600 uppercase border-b">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">#Order</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Items</th>
                            <th class="px-4 py-3 text-center">Total</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Detail</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-pink-50 transition-colors duration-200">
                                <!-- Nomor -->
                                <td class="px-3 py-4">
                                    {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>

                                <!-- Order Number -->
                                <td class="px-3 py-4 text-left font-medium text-gray-900 whitespace-nowrap">
                                    #{{ $order->order_number }}</td>

                                <!-- Customer -->
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-pink-100 rounded-full flex items-center justify-center">
                                            @if ($order->user->profile_photo)
                                                <img src="{{ asset('storage/' . $order->user->profile_photo) }}"
                                                    alt="Profile Photo" class="w-10 h-10 rounded-full">
                                            @else
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user-circle  text-pink-600 text-[36px]"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
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
                                <td class="px-3 py-4 font-semibold text-gray-900 text-center">
                                    Rp{{ number_format($order->total, 0, ',', '.') }}
                                </td>

                                <!-- Status -->
                                <td class="px-3 py-4 text-center">
                                    @if ($order->status == 'unpaid')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 animate-pulse">
                                            <i class="fa-solid fa-circle-dollar-to-slot mr-1 "></i>Unpaid
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
                                </td>

                                <!-- Detail -->
                                <td class="px-3 py-4 text-center">
                                    @if ($order->status != 'unpaid')
                                        <button onclick="openModal({{ $order->id }})"
                                            class="inline-flex items-center justify-center p-2 rounded-full text-pink-500 hover:bg-pink-100 transition-colors"
                                            title="View Details">
                                            <i class="fa-solid fa-eye text-lg"></i>
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-sm">Unpaid</span>
                                    @endif
                                </td>

                                <!-- Action -->
                                <td class="px-3 py-4 text-center">
                                    @if ($order->status != 'unpaid')
                                        <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                class="text-sm border border-pink-300 rounded px-2 py-1 focus:ring-pink-500 focus:border-pink-500">
                                                <option value="waiting_confirmation"
                                                    {{ $order->status == 'waiting_confirmation' ? 'selected' : '' }}>
                                                    Process
                                                </option>
                                                <option value="approved"
                                                    {{ $order->status == 'approved' ? 'selected' : '' }}>
                                                    Approved
                                                </option>
                                                <option value="denied" {{ $order->status == 'denied' ? 'selected' : '' }}>
                                                    Denied
                                                </option>
                                            </select>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-sm">Unpaid</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-gray-400">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $orders->appends(['perPage' => $perPage])->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

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
                            action="{{ route('admin.order.exportPdf', $order->id) }}" method="GET"
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
