@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-pink-100 sm:ml-64 pt-28 pb-12 px-6">
        <div class="max-w-8xl mx-auto space-y-8">
            @if (session('success'))
                <x-success-toast title="Success!!" message="{{ session('success') }}" />
            @endif

            @if (session('error_title'))
                <x-error-toast title="{{ session('error_title') }}" message="{{ session('error_message') }}" />
            @endif

            <!-- Header -->
            <div class="flex items-center justify-between p-6 bg-white shadow-lg rounded-2xl">
                <h2 class="text-2xl font-semibold text-pink-600">
                    <i class="fa-solid fa-tags mr-2"></i>Vouchers
                </h2>
                <button onclick="openAddVoucherModal()"
                    class="bg-pink-600 text-white px-4  font-semibold py-2 rounded-lg hover:bg-pink-700 transition duration-300">
                    <i class="fas fa-plus mr-2"></i> Add Voucher
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto bg-white p-6 shadow-lg rounded-2xl">
                <!-- Controls -->
                <div class="flex justify-between items-center mb-4">
                    <!-- Show -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-pink-600">Show:</span>
                        <form id="perPageForm" method="GET" action="{{ route('admin.voucher.index') }}">
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

                    <div class="relative flex items-center space-x-2">
                        <form method="GET" action="{{ route('admin.voucher.index') }}"
                            class="flex items-center space-x-2 relative">
                            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                            <div class="relative">
                                <input type="text" name="search" placeholder="Search..."
                                    value="{{ request('search') }}"
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

                <table class="min-w-full text-center text-sm">
                    <thead>
                        <tr class="text-pink-600 uppercase border-b">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4 text-left whitespace-nowrap">Voucher Code</th>
                            <th class="py-3 px-4">Discount(%)</th>
                            <th class="py-3 px-4 whitespace-nowrap">Expired Date</th>
                            <th class="py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($vouchers as $voucher)
                            <tr class="border-b hover:bg-pink-50">
                                <td class="py-3 px-4">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-pink-600 text-left">{{ strtoupper($voucher->voucher_code) }}
                                </td>
                                <td class="py-3 px-4">{{ $voucher->discount_percentage }}%</td>
                                <td class="py-3 px-4 whitespace-nowrap">{{ $voucher->expired_at->format('d M Y') }}</td>
                                <td class="py-3 px-4 space-x-2 whitespace-nowrap">
                                    <button
                                        onclick="openEditModal(
                                                '{{ route('admin.voucher.update', $voucher) }}',
                                                '{{ $voucher->voucher_code }}',
                                                '{{ $voucher->discount_percentage }}',
                                                '{{ $voucher->expired_at->format('Y-m-d') }}'
                                            )"
                                        class="text-blue-600 hover:text-blue-700 font-semibold transition duration-300">
                                        <i class="fas fa-edit mr-1 text-lg"></i>
                                    </button>
                                    <button onclick="openDeleteModal('{{ route('admin.voucher.destroy', $voucher) }}')"
                                        class="text-red-600 hover:text-red-800" title="Hapus voucher">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500 italic">no vouchers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $vouchers->appends(request()->all())->links('pagination::tailwind') }}
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Tambah  -->
    <div id="modal-add-voucher" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-md space-y-4 shadow-xl animate-fade-in">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-pink-600">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Voucher
                </h3>
                <button onclick="closeAddVoucherModal()" aria-label="Tutup modal"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:text-white hover:bg-red-500 text-xl transition duration-300">
                    <i class="fas fa-times relative top-[1px]"></i>
                </button>
            </div>

            <form x-data="{ loading: false }" @submit="loading = true" method="POST"
                action="{{ route('admin.voucher.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Voucher</label>
                        <input type="text" name="voucher_code"
                            class="mt-1 uppercase block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 transition duration-300"
                            placeholder="kode_voucher" required  onkeydown="return event.key !== ' '">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diskon (%)</label>
                        <input type="number" name="discount_percentage" min="1" max="100"
                            class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 transition duration-300"
                            placeholder="Masukkan persentase diskon..." required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                        <input type="date" name="expired_at"
                            class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 transition duration-300"
                            required>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <x-spinner-button loading="loading" label="Simpan" loadingLabel="Menyimpan..." bgColor="bg-pink-600"
                        hoverColor="hover:bg-pink-700" ringColor="focus:ring-pink-500"
                        icon='<i class="fa-solid fa-floppy-disk mr-2"></i>' />
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit  -->
    <div id="modal-edit-voucher"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-md space-y-4 shadow-xl animate-fade-in">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-blue-600">
                    <i class="fas fa-edit mr-2"></i>Edit Voucher
                </h3>
                <button onclick="closeEditModal()" aria-label="Tutup modal"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:text-white hover:bg-red-500 text-xl transition duration-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editForm" method="POST" action="" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Voucher</label>
                        <input type="text" name="voucher_code" id="editCode"
                            class="mt-1 block w-full px-4 py-2 uppercase border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diskon (%)</label>
                        <input type="number" name="discount_percentage" id="editDiscount" min="1"
                            max="100"
                            class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                        <input type="date" name="expired_at" id="editExpiredAt"
                            class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                            required>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <x-spinner-button loading="loading" label="Update" loadingLabel="Mengupdate..."
                        bgColor="bg-blue-600" hoverColor="hover:bg-blue-700" ringColor="focus:ring-blue-500"
                        icon='<i class="fa-solid fa-floppy-disk mr-2"></i>' />
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete  -->
    <div id="modal-delete-voucher"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-sm space-y-4 shadow-xl animate-fade-in text-center">
            <div class="text-red-600 text-3xl">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Hapus Voucher?</h3>
            <p class="text-sm text-gray-600">Apakah kamu yakin ingin menghapus voucher ini? Tindakan ini tidak dapat
                dibatalkan.</p>
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


    <script>
        // Modal
        function openAddVoucherModal() {
            document.getElementById('modal-add-voucher').classList.remove('hidden');
        }

        function closeAddVoucherModal() {
            document.getElementById('modal-add-voucher').classList.add('hidden');
        }

        function openEditModal(actionUrl, code, discount, expiredAt) {
            document.getElementById('editForm').action = actionUrl;

            document.getElementById('editCode').value = code;
            document.getElementById('editDiscount').value = discount;
            document.getElementById('editExpiredAt').value = expiredAt;

            document.getElementById('modal-edit-voucher').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('modal-edit-voucher').classList.add('hidden');
        }

        function openDeleteModal(actionUrl) {
            const modal = document.getElementById('modal-delete-voucher');
            const form = document.getElementById('deleteForm');
            form.action = actionUrl;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('modal-delete-voucher').classList.add('hidden');
        }
    </script>
@endsection
