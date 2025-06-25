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
                    <i class="fas fa-credit-card mr-2"></i>Payment Methods
                </h2>
                <button
                    class="flex items-center bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300"
                    onclick="openAddModal()">
                    <i class="fas fa-plus mr-2"></i>Add Payment
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto bg-white p-6 shadow-lg rounded-2xl">
                <!-- Controls -->
                <div class="flex justify-between items-center mb-4">
                    <!-- Show -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-pink-600">Show:</span>
                        <form id="perPageForm" method="GET" action="{{ route('admin.payment-methods.index') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="sort" value="{{ request('sort', 'desc') }}">
                        
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
                        <form method="GET" action="{{ route('admin.payment-methods.index') }}" class="flex items-center space-x-2 relative">
                            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                        
                            <!-- Input Search -->
                            <div class="relative">
                                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                                    class="border rounded-lg px-4 py-2 border-pink-400 pl-10 text-sm focus:ring-pink-500 focus:border-pink-500">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-pink-500"></i>
                            </div>
                        
                            <!-- Sort Dropdown -->
                            <div class="relative">
                                <select name="sort"
                                    class="border rounded-lg px-4 py-2 border-pink-400 text-sm focus:ring-pink-500 focus:border-pink-500"
                                    onchange="this.form.submit()">
                                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Latest</option>
                                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Oldest</option>
                                </select>
                            </div>
                        </form>                        
                    </div>
                </div>

                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="text-pink-600 uppercase border-b">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Img</th>
                            <th class="py-3 px-4">Bank</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4 whitespace-nowrap">account number</th>
                            <th class="py-3 px-4 whitespace-nowrap">Updated At</th>
                            <th class="py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($paymentMethods as $payment)
                            <tr class="border-b hover:bg-pink-50">
                                <td class="py-3 px-4">
                                    {{ $loop->iteration + ($paymentMethods->currentPage() - 1) * $paymentMethods->perPage() }}
                                </td>
                                <td class="py-3 px-4">
                                    <img src="{{ asset('storage/' . $payment->gambar) }}"
                                        class="rounded-lg w-10 h-10 object-cover" alt="img">
                                </td>
                                <td class="py-3 px-4">{{ $payment->bank }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">{{ $payment->nama }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">{{ $payment->no_rekening }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">{{ $payment->updated_at->format('d-m-Y') }}</td>
                                <td class="py-3 px-4 space-x-2 whitespace-nowrap">
                                    <button
                                        onclick="openEditModal({{ $payment->id }}, '{{ $payment->nama }}', '{{ $payment->bank }}', '{{ $payment->no_rekening }}', '{{ asset('storage/' . $payment->gambar) }}')"
                                        class="text-blue-600 hover:text-blue-700 font-semibold transition duration-300">
                                        <i class="fas fa-edit mr-1 text-lg"></i>
                                    </button>
                                    <button onclick="openDeleteModal({{ $payment->id }})"
                                        class="text-red-600 hover:text-red-700 font-semibold transition duration-300">
                                        <i class="fas fa-trash-alt mr-1 text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center pt-10 text-gray-500 italic">No payment methods found.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                <div class="mt-4">
                    <div class="mt-4">
                        {{ $paymentMethods->appends(['perPage' => $perPage])->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="modal-add-payment" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-3xl space-y-6 shadow-xl animate-fade-in">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-pink-600">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Metode Pembayaran
                </h3>
                <button onclick="closeAddModal()" aria-label="Close modal"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:text-white hover:bg-red-500 text-xl transition duration-300">
                    <i class="fas fa-times relative top-[1px]"></i>
                </button>
            </div>

            <form x-data="{ loading: false }" @submit="loading = true" method="post"
                action="{{ route('admin.payment-methods.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Form Fields -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="nama"
                                class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 transition duration-300"
                                placeholder="Masukkan nama pemilik rekening..." required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bank</label>
                            <input type="text" name="bank"
                                class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 transition duration-300"
                                placeholder="Masukkan nama bank..." required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No Rekening</label>
                            <input type="text" name="no_rekening" pattern="[0-9]*" inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 transition duration-300"
                                placeholder="Masukkan nomor rekening..." required>
                        </div>
                    </div>

                    <!-- Upload Image -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar</label>
                            <div class="flex items-center gap-4 mt-2 mb-4">
                                <img id="preview" src="" alt="Preview"
                                    class="w-24 h-24 object-cover rounded-lg hidden" />
                                <div id="filename" class="text-sm text-gray-700 hidden break-all"></div>
                            </div>
                            <div class="flex items-center justify-center w-full">
                                <label
                                    class="flex flex-col w-full h-32 border-2 border-dashed rounded-lg hover:bg-gray-100 transition duration-300 cursor-pointer">
                                    <div class="flex flex-col items-center justify-center pt-7">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-500">Click to upload</p>
                                    </div>
                                    <input id="addGambarInput" type="file" name="gambar" class="opacity-0 absolute"
                                        accept="image/*" onchange="previewImage(event)" required>
                                </label>
                            </div>
                        </div>
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

    <!-- Modal Edit -->
    <div id="modal-edit-payment"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-3xl space-y-6 shadow-xl animate-fade-in">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-blue-600">
                    <i class="fas fa-edit mr-2"></i>Edit Metode Pembayaran
                </h3>
                <button onclick="closeEditModal()" aria-label="Close modal"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:text-white hover:bg-red-500 text-xl transition duration-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editForm" method="POST" action="" enctype="multipart/form-data" x-data="{ loading: false }"
                @submit="loading = true">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Form Fields -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" id="editNama" name="nama"
                                class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                                placeholder="Masukkan nama pemilik rekening..." required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bank</label>
                            <input type="text" id="editBank" name="bank"
                                class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                                placeholder="Masukkan nama bank..." required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No Rekening</label>
                            <input type="text" id="editNoRekening" name="no_rekening" pattern="[0-9]*"
                                inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"
                                placeholder="Masukkan nomor rekening..." required>
                        </div>
                    </div>

                    <!-- Image Section -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Saat Ini</label>
                            <div id="editImageWrapper" class="flex items-center gap-4 mt-4">
                                <img id="editCurrentImage" src=""
                                    class="rounded-lg w-24 h-24 object-cover" alt="Current Image">
                                <span class="text-sm text-gray-500">Change image below</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Baru</label>
                            <div class="flex items-center justify-center w-full">
                                <label
                                    class="flex flex-col w-full h-32 border-2 border-dashed rounded-lg hover:bg-gray-100 transition duration-300 cursor-pointer">
                                    <div class="flex flex-col items-center justify-center pt-7">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-500">Click to upload</p>
                                    </div>
                                    <input id="editGambarInput" name="gambar" type="file" class="opacity-0 absolute"
                                        accept="image/*" onchange="previewEditImage(event)">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <x-spinner-button loading="loading" label="Edit" loadingLabel="Mengedit..." bgColor="bg-blue-600"
                        hoverColor="hover:bg-blue-700" ringColor="focus:ring-blue-500"
                        icon='<i class="fa-solid fa-edit mr-2"></i>' />
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete  -->
    <div id="modal-delete-payment"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-sm space-y-4 shadow-xl animate-fade-in text-center">
            <div class="text-red-600 text-3xl">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Hapus Metode Pembayaran?</h3>
            <p class="text-sm text-gray-600">
                Apakah kamu yakin ingin menghapus metode pembayaran ini? Tindakan ini tidak dapat dibatalkan.
            </p>
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
        function openAddModal() {
            document.getElementById('modal-add-payment').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('modal-add-payment').classList.add('hidden');
        }

        function openEditModal(id, nama, bank, no_rekening, gambarUrl) {
            const modal = document.getElementById('modal-edit-payment');
            const form = document.getElementById('editForm');

            form.action = "{{ url('admin/payment') }}/" + id;

            document.getElementById('editNama').value = nama;
            document.getElementById('editBank').value = bank;
            document.getElementById('editNoRekening').value = no_rekening;
            document.getElementById('editCurrentImage').src = gambarUrl;

            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('modal-edit-payment').classList.add('hidden');
            document.getElementById('editGambarInput').value = '';
        }

        function openDeleteModal(id) {
            const modal = document.getElementById('modal-delete-payment');
            const form = document.getElementById('deleteForm');
            form.action = "{{ url('admin/payment') }}/" + id;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('modal-delete-payment').classList.add('hidden');
        }

        // Preview Add Image
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            const filenameDisplay = document.getElementById('filename');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');

                    filenameDisplay.textContent = "File: " + file.name;
                    filenameDisplay.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Preview Edit Image
        function previewEditImage(event) {
            const input = event.target;
            const preview = document.getElementById('editCurrentImage');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
