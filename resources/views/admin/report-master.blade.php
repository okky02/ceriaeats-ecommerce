@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-pink-100 sm:ml-64 pt-28 pb-12 px-6">
        <div class="max-w-8xl mx-auto space-y-8">

            <!-- Header -->
            <div class="flex items-center justify-between p-6 bg-white shadow-lg rounded-2xl">
                <h2 class="text-2xl font-semibold text-pink-600">
                    <i class="fas fa-chart-bar mr-2"></i>Reports
                </h2>
                <form x-data="{ loading: false }" x-ref="formEl"
                    @submit.prevent="
                                        loading = true;
                                        $nextTick(() => {
                                            const iframe = document.querySelector('iframe[name=downloadFrame]');
                                            
                                            setTimeout(() => loading = false, 5000); 

                                            iframe.onload = () => {
                                                loading = false;
                                            };

                                            // Submit secara manual
                                            $refs.formEl.submit();
                                        });
                                    "
                    method="GET" action="{{ route('admin.report.exportPdf', request()->query()) }}" target="downloadFrame"
                    x-ref="formEl">
                    
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">

                    <x-spinner-button loading="loading" label="Download PDF" loadingLabel="Downloading..."
                        bgColor="bg-pink-600" hoverColor="hover:bg-pink-700"
                        ringColor="focus:ring-pink-500 focus:ring-offset-2" textSize="text-base font-semibold"
                        padding="py-2 px-4" rounded="rounded-lg" fullWidth="false"
                        icon='<i class="fas fa-download mr-2"></i>' />
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto bg-white p-6 shadow-lg rounded-2xl">
                <!-- Controls -->
                <div class="flex justify-between items-center mb-4">
                    <!-- Show -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-pink-600">Show:</span>
                        <form id="perPageForm" method="GET" action="{{ route('admin.report.index') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">

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

                    <div class="relative flex items-center space-x-2">
                        <form method="GET" action="{{ route('admin.report.index') }}"
                            class="flex items-center space-x-2 relative">
                            <!-- PerPage hidden -->
                            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">

                            <!-- Search -->
                            <div class="relative">
                                <input type="text" name="search" placeholder="Search..."
                                    value="{{ request('search') }}"
                                    class="border rounded-lg px-4 py-2 border-pink-400 pl-10 text-sm focus:ring-pink-500 focus:border-pink-500">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-pink-500"></i>
                            </div>

                            <!-- Sort -->
                            <div class="relative">
                                <select name="sort"
                                    class="border rounded-lg px-4 py-2 border-pink-400 text-sm focus:ring-pink-500 focus:border-pink-500"
                                    onchange="this.form.submit()">
                                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Latest
                                    </option>
                                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Oldest</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="relative flex items-center space-x-1">
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="border rounded-lg px-3 py-2 border-pink-400 text-sm focus:ring-pink-500 focus:border-pink-500">
                                <span class="text-sm text-gray-500">to</span>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="border rounded-lg px-3 py-2 border-pink-400 text-sm focus:ring-pink-500 focus:border-pink-500">
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
                        </form>

                    </div>
                </div>

                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="text-pink-600 uppercase border-b">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Order #</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($orders as $index => $order)
                            <tr class="border-b hover:bg-pink-50">
                                <td class="py-3 px-4">
                                    {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $order->order_number }}</td>
                                <td class="py-3 px-4">{{ $order->user->name }}</td>
                                <td class="py-3 px-4">{{ $order->created_at->format('Y-m-d') }}</td>
                                <td class="py-3 px-4">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500 italic">No reports found.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                <div class="mt-4">
                    {{ $orders->appends(request()->except('page'))->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
@endsection
