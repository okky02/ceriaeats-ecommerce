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
                    <i class="fas fa-boxes mr-2"></i>Products
                </h2>
                <button
                    class="flex items-center bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-300"
                    onclick="openAddModal()">
                    <i class="fas fa-plus mr-2"></i>Add Product
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto bg-white p-6 shadow-lg rounded-2xl">
                <!-- Controls -->
                <div class="flex justify-between items-center mb-4">
                    <!-- Show -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-pink-600">Show:</span>
                        <form id="perPageForm" method="GET" action="{{ route('admin.product.index') }}">
                            <!-- Hidden agar semua filter tetap terpakai -->
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
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

                    <!-- Search, Kategori & Sort -->
                    <div class="relative flex items-center space-x-2">
                        <form method="GET" action="{{ route('admin.product.index') }}" class="flex items-center space-x-2 relative">
                            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                        
                            <!-- Search -->
                            <div class="relative">
                                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                                    class="border rounded-lg px-4 py-2 border-pink-400 pl-10 text-sm focus:ring-pink-500 focus:border-pink-500">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-pink-500"></i>
                            </div>
                        
                            <!-- Category -->
                            <div class="relative">
                                <select name="category_id"
                                    class="border rounded-lg px-4 py-2 border-pink-400 text-sm focus:ring-pink-500 focus:border-pink-500"
                                    onchange="this.form.submit()">
                                    <option value="">All Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <!-- Sort -->
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
                            <th class="py-3 px-4">Product Name</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Price</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4 whitespace-nowrap">Updated At</th>
                            <th class="py-3 px-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($products as $product)
                            <tr class="border-b hover:bg-pink-50">
                                <td class="py-3 px-4">
                                    {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <td class="py-3 px-4">
                                    <img src="{{ asset('storage/' . $product->gambar) }}"
                                        class="rounded-lg w-10 h-10 object-cover" alt="img">
                                </td>
                                <td class="py-3 px-4">{{ $product->nama_produk }}</td>
                                <td class="py-3 px-4">{{ $product->category->nama_kategori ?? 'No Category' }}</td>
                                <td class="py-3 px-4 whitespace-nowrap">Rp. {{ number_format($product->harga, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">{{ $product->deskripsi }}</td>
                                <td class="py-3 px-4 whitespace-nowrap ">{{ $product->updated_at->format('d-m-Y') }}</td>
                                <td class="py-3 px-4 space-x-2 whitespace-nowrap ">
                                    <button
                                        onclick="openEditModal({{ $product->id }}, '{{ $product->nama_produk }}', {{ $product->category_id ?? 'null' }}, {{ $product->harga }}, '{{ $product->deskripsi }}', '{{ asset('storage/' . $product->gambar) }}')"
                                        class="text-blue-600 hover:text-blue-700 font-semibold">
                                        <i class="fas fa-edit mr-1 text-lg"></i>
                                    </button>
                                    <button onclick="openDeleteModal({{ $product->id }})"
                                        class="text-red-600 hover:text-red-700 font-semibold">
                                        <i class="fas fa-trash-alt mr-1 text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center pt-10 text-gray-500 italic">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $products->appends(request()->except('page'))->links() }}

                </div>
            </div>

        </div>

        <!-- Modal Tambah -->
        <div id="modal-add-product"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
            <div class="bg-white p-6 rounded-xl w-full max-w-3xl space-y-6 shadow-xl animate-fade-in">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-pink-600">
                        <i class="fas fa-plus-circle mr-2"></i>Tambah Produk
                    </h3>
                    <button onclick="closeAddModal()" aria-label="Close modal"
                        class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:text-white hover:bg-red-500 text-xl transition duration-300">
                        <i class="fas fa-times relative top-[1px]"></i>
                    </button>
                </div>

                <form x-data="{ loading: false }" @submit="loading = true" method="post"
                    action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                                <input type="text" name="nama_produk"
                                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 transition duration-300"
                                    placeholder="Masukkan nama produk..." required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <select name="category_id"
                                    class="border rounded-lg px-4 py-2 w-full text-sm focus:ring-pink-500 focus:border-pink-500">
                                    <option value="">Pilih kategori...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                                <input type="text" name="harga" id="hargaInput"
                                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 transition duration-300"
                                    placeholder="Masukkan harga..." required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk</label>
                                <textarea name="deskripsi"
                                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 transition duration-300"
                                    placeholder="Masukkan deskripsi..." required></textarea>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar</label>
                                <div class="flex items-center gap-4 mt-2 mb-4">
                                    <img id="preview" src="" alt="Preview"
                                        class="w-24 h-24 object-cover rounded-lg hidden border border-pink-300" />
                                    <div id="filename" class="text-sm text-gray-700 hidden break-all"></div>
                                </div>
                                <div class="flex items-center justify-center w-full">
                                    <label
                                        class="flex flex-col w-full h-32 border-2 border-dashed rounded-lg hover:bg-gray-100 transition duration-300 cursor-pointer">
                                        <div class="flex flex-col items-center justify-center pt-7">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-500">Click to upload</p>
                                        </div>
                                        <input id="addGambarInput" type="file" name="gambar"
                                            class="opacity-0 absolute" accept="image/*" onchange="previewImage(event)"
                                            required>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-3">
                        <x-spinner-button loading="loading" label="Simpan" loadingLabel="Menyimpan..."
                            bgColor="bg-pink-600" hoverColor="hover:bg-pink-700" ringColor="focus:ring-pink-500"
                            icon='<i class="fa-solid fa-floppy-disk mr-2"></i>' />
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit -->
        <div id="modal-edit-product"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
            <div class="bg-white p-6 rounded-xl w-full max-w-3xl space-y-6 shadow-xl animate-fade-in">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-blue-600">
                        <i class="fas fa-edit mr-2"></i>Edit Produk
                    </h3>
                    <button onclick="closeEditModal()" aria-label="Close modal"
                        class="w-8 h-8 flex items-center justify-center rounded-full text-red-500 hover:text-white hover:bg-red-500 text-xl transition duration-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="editForm" method="POST" action="" enctype="multipart/form-data"
                    x-data="{ loading: false }" @submit="loading = true" onsubmit="beforeEditSubmit()">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                                <input type="text" id="editNamaProduk" name="nama_produk" value=""
                                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <select id="editKategori" name="category_id"
                                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300">
                                    <option value="">Pilih kategori...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                                <input type="text" id="editHarga" name="harga" value=""
                                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk</label>
                                <textarea id="editDeskripsi" name="deskripsi"
                                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300"></textarea>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Saat Ini</label>
                                <div id="editImageWrapper" class="flex items-center space-x-4 mt-4">
                                    <img id="editCurrentImage" src="" class="rounded-lg w-24 h-24 object-cover"
                                        alt="Current Image">
                                    <span class="text-sm text-gray-500">Ganti gambar di bawah</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Baru</label>
                                <div class="flex items-center justify-center w-full">
                                    <label
                                        class="flex flex-col w-full h-32 border-2 border-dashed rounded-lg hover:bg-gray-100 transition duration-300 cursor-pointer">
                                        <div class="flex flex-col items-center justify-center pt-7">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-500">Click untuk upload</p>
                                        </div>
                                        <input id="editGambarInput" name="gambar" type="file"
                                            class="opacity-0 absolute" accept="image/*"
                                            onchange="previewEditImage(event)">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-3">
                        <x-spinner-button loading="loading" label="Edit" loadingLabel="Mengedit..."
                            bgColor="bg-blue-600" hoverColor="hover:bg-blue-700" ringColor="focus:ring-blue-500"
                            icon='<i class="fa-solid fa-floppy-disk mr-2"></i>' />
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Delete -->
        <div id="modal-delete-product"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
            <div class="bg-white p-6 rounded-xl w-full max-w-sm space-y-4 shadow-xl animate-fade-in text-center">
                <div class="text-red-600 text-3xl">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Hapus Produk?</h3>
                <p class="text-sm text-gray-600">Apakah kamu yakin ingin menghapus produk ini? Tindakan ini tidak dapat
                    dibatalkan.</p>
                <div class="flex justify-center mt-6 space-x-3">
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-300">
                        Batal
                    </button>
                    <form id="deleteForm" x-data="{ loading: false }" @submit="loading = true" method="POST"
                        action="">
                        @csrf
                        @method('DELETE')
                        <x-spinner-button loading="loading" label="Hapus" loadingLabel="Menghapus..."
                            bgColor="bg-red-600" hoverColor="hover:bg-red-700" ringColor="focus:ring-red-500"
                            icon='<i class="fa-solid fa-trash-alt mr-2"></i>' />
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Modal
            function openAddModal() {
                document.getElementById('modal-add-product').classList.remove('hidden');
            }

            function closeAddModal() {
                document.getElementById('modal-add-product').classList.add('hidden');
                document.getElementById('addGambarInput').value = '';
            }

            function openEditModal(id, namaProduk, kategoriId, harga, deskripsi, gambarUrl) {
                const modal = document.getElementById('modal-edit-product');
                const form = document.getElementById('editForm');
                const inputNama = document.getElementById('editNamaProduk');
                const inputKategori = document.getElementById('editKategori');
                const inputHarga = document.getElementById('editHarga');
                const inputDeskripsi = document.getElementById('editDeskripsi');
                const image = document.getElementById('editCurrentImage');

                inputNama.value = namaProduk;
                inputHarga.value = new Intl.NumberFormat('id-ID').format(Math.floor(harga));
                inputKategori.value = kategoriId;
                inputDeskripsi.value = deskripsi;
                image.src = gambarUrl;
                form.action = "{{ url('admin/product') }}/" + id;

                modal.classList.remove('hidden');
            }

            function closeEditModal() {
                const modal = document.getElementById('modal-edit-product');
                const form = document.getElementById('editForm');
                const inputNama = document.getElementById('editNamaProduk');
                const inputKategori = document.getElementById('editKategori');
                const inputHarga = document.getElementById('editHarga');
                const inputDeskripsi = document.getElementById('editDeskripsi');
                const inputGambar = document.getElementById('editGambarInput');
                const imagePreview = document.getElementById('editCurrentImage');

                inputNama.value = '';
                inputKategori.value = '';
                inputHarga.value = '';
                inputDeskripsi.value = '';
                inputGambar.value = '';
                imagePreview.src = '';

                form.action = '';

                modal.classList.add('hidden');
            }

            function openDeleteModal(id) {
                const modal = document.getElementById('modal-delete-product');
                const form = document.getElementById('deleteForm');
                form.action = "{{ url('admin/product') }}/" + id;
                modal.classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('modal-delete-product').classList.add('hidden');
            }

            // Sort Dropdown
            function toggleSortDropdown() {
                document.getElementById('sortDropdown').classList.toggle('hidden');
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

            // Price Input Format Modal Add
            const hargaInput = document.getElementById('hargaInput');
            hargaInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                value = new Intl.NumberFormat('id-ID').format(value);
                this.value = value;
            });

            // Price Input Format Modal Edit
            const editHargaInput = document.getElementById('editHarga');
            editHargaInput.addEventListener('input', function(e) {
                let value = this.value.replace(/\D/g, '');
                if (value) {
                    value = parseInt(value, 10).toString();
                    this.value = new Intl.NumberFormat('id-ID').format(value);
                } else {
                    this.value = '';
                }
            });

            function beforeEditSubmit() {
                const hargaInput = document.getElementById('editHarga');
                hargaInput.value = hargaInput.value.replace(/\D/g, '');
                return true;
            }
        </script>
    @endsection
