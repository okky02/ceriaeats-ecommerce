@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-pink-50 sm:ml-64 pt-28 pb-12 px-4 md:px-8">
        <div class="max-w-8xl mx-auto space-y-8">
            <!-- Header Section -->
            <div class="flex justify-between items-center bg-white rounded-xl shadow-md p-6">
                <h1 class="text-2xl font-bold text-pink-600">
                    <i class="fa-solid fa-gauge-high mr-2"></i>Dashboard Overview
                </h1>
                <div class="text-sm text-pink-600">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ now()->format('l, F j, Y') }}
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-pink-500 hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Orders</p>
                            <p class="text-2xl font-bold mt-1">{{ $totalOrders }}</p>
                        </div>
                        <div class="bg-pink-100 p-3 rounded-full">
                            <i class="fas fa-shopping-cart text-pink-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Customers</p>
                            <p class="text-2xl font-bold mt-1">{{ $totalUsers }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Products</p>
                            <p class="text-2xl font-bold mt-1">{{ $totalProducts }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-boxes text-purple-600"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Categories</p>
                            <p class="text-2xl font-bold mt-1">{{ $totalCategories }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fa-solid fa-box text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales and Status Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sales Card -->
                <div class="bg-white rounded-xl shadow-md p-6 lg:col-span-1">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Total Sales</h2>
                        <i class="fas fa-chart-line text-pink-500"></i>
                    </div>
                    <p class="text-3xl font-bold text-green-600 mb-2">Rp{{ number_format($totalSales, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Total revenue from all completed orders
                    </p>
                </div>

                <!-- Order Status Cards -->
                <div class="grid grid-cols-2 gap-4 lg:col-span-2">
                    @foreach ($orderStatusCounts as $status => $count)
                        <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-shadow">
                            <div class="flex items-center">
                                <div
                                    class="p-2 rounded-full mr-3 
                            @if ($status == 'unpaid') bg-gray-100 text-gray-600
                            @elseif($status == 'process') bg-blue-100 text-blue-600
                            @elseif($status == 'approved') bg-green-100 text-green-600
                            @elseif($status == 'denied') bg-red-100 text-red-600 @endif">
                                    @if ($status == 'unpaid')
                                        <i class="fa-solid fa-circle-dollar-to-slot "></i>
                                    @elseif($status == 'process')
                                        <i class="fas fa-hourglass-half"></i>
                                    @elseif($status == 'approved')
                                        <i class="fas fa-check"></i>
                                    @elseif($status == 'denied')
                                        <i class="fas fa-times"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 capitalize">
                                        {{ str_replace('_', ' ', $status) }}</p>
                                    <p class="text-xl font-bold">{{ $count }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Grafik Wrapper -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sales Chart Section -->
                <div class="bg-white rounded-xl shadow-md p-4 h-[400px] flex flex-col">
                    <h2 class="text-lg font-semibold mb-4">Sales Chart Per Month ({{ date('Y') }})</h2>
                    <div class="flex-1">
                        <canvas id="salesChart" class="w-full" style="height: 100%;"></canvas>
                    </div>
                </div>

                <!-- Top Products Section -->
                <div class="bg-white rounded-xl shadow-md p-4 h-[400px] flex flex-col">
                    <h2 class="text-lg font-semibold mb-4">Top 5 Best Selling Products</h2>
                    <div class="flex-1">
                        <canvas id="topProductsChart" class="w-full" style="height: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
                        <a href="{{ route('admin.order.index') }}"
                            class="text-sm text-pink-600 hover:text-pink-800 flex items-center">
                            View All <i class="fas fa-chevron-right ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($recentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp{{ number_format($order->total, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($order->status == 'unpaid')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fa-solid fa-circle-dollar-to-slot mr-1 "></i>Unpaid
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <i class="fas fa-inbox text-3xl mb-2 text-gray-300"></i>
                                        <p>No recent orders found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Reviews Section -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Reviews Products</h2>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rating</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Review</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($recentReviews as $review)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-md overflow-hidden">
                                                @if ($review->product->gambar)
                                                    <img class="h-full w-full object-cover"
                                                        src="{{ asset('storage/' . $review->product->gambar) }}"
                                                        alt="{{ $review->product->nama_produk }}">
                                                @else
                                                    <div
                                                        class="h-full w-full bg-gray-200 flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($review->product->nama_produk, 20) }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $review->product->category->nama_kategori ?? 'Uncategorized' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-pink-100 rounded-full flex items-center justify-center">
                                                @if ($review->user->profile_photo)
                                                    <img src="{{ asset('storage/' . $review->user->profile_photo) }}"
                                                        alt="Profile Photo" class="w-10 h-10 rounded-full">
                                                @else
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-user-circle  text-pink-600 text-[36px]"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $review->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $review->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                @else
                                                    <i class="far fa-star text-yellow-400"></i>
                                                @endif
                                            @endfor
                                            <span class="ml-1 text-xs text-gray-500">({{ $review->rating }})</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $review->review ? Str::limit($review->review, 50) : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $review->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $review->created_at->format('h:i A') }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 space-x-2 items-center">
                                        <button 
                                            class="text-red-600 hover:text-red-700 font-semibold transition duration-300">
                                            <i class="fas fa-trash-alt mr-1 text-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        <i class="fas fa-comment-slash text-3xl mb-2 text-gray-300"></i>
                                        <p>No recent reviews found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // ==== SALES CHART ====
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: {!! json_encode($salesData) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)', // warna biru lembut
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 6, // biar bar-nya bulat ujungnya
                    barThickness: 30
                }]
            },
            options: {
                maintainAspectRatio: false, // penting untuk atur tinggi manual
                layout: {
                    padding: 10
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp' + value.toLocaleString('id-ID')
                        },
                        grid: {
                            color: '#f0f0f0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: context => {
                                let val = context.parsed.y || 0;
                                return 'Rp' + val.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });


        // ==== TOP PRODUCTS CHART ====
        const topProducts = @json($topProducts);
        const productLabels = topProducts.map(p => p.nama_produk);
        const productSales = topProducts.map(p => p.total_terjual);

        const ctxTopProducts = document.getElementById('topProductsChart').getContext('2d');
        new Chart(ctxTopProducts, {
            type: 'bar',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'Total Terjual',
                    data: productSales,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)', // hijau toska soft
                    borderColor: 'rgba(5, 150, 105, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y', // horizontal bars
                maintainAspectRatio: false,
                layout: {
                    padding: 10
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.parsed.x} terjual`
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: '#f0f0f0'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endsection
