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
            <div class=" p-6 bg-white shadow-lg rounded-2xl">
                <h2 class="text-2xl font-semibold text-pink-600">
                    <i class="fa-solid fa-users mr-2"></i>Users
                </h2>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto bg-white p-6 shadow-lg rounded-2xl">
                <!-- Controls -->
                <div class="flex justify-between items-center mb-4">
                    <!-- Show -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-pink-600">Show:</span>
                        <form id="perPageForm" method="GET" action="{{ route('admin.users.index') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="role" value="{{ request('role') }}">
                            <input type="hidden" name="sort" value="{{ request('sort', 'asc') }}">

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

                    <!-- Search, Role & Sort -->
                    <div class="relative flex items-center space-x-2">
                        <form method="GET" action="{{ route('admin.users.index') }}"
                            class="flex items-center space-x-2 relative">
                            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">

                            <!-- Input Search -->
                            <div class="relative">
                                <input type="text" name="search" placeholder="Search..."
                                    value="{{ request('search') }}"
                                    class="border rounded-lg px-4 py-2 border-pink-400 pl-10 text-sm focus:ring-pink-500 focus:border-pink-500">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-pink-500"></i>
                            </div>

                            <!-- Role Dropdown -->
                            <div class="relative">
                                <select name="role"
                                    class="border rounded-lg px-4 py-2 border-pink-400 text-sm focus:ring-pink-500 focus:border-pink-500"
                                    onchange="this.form.submit()">
                                    <option value="">All Roles</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>

                            <!-- Sort Dropdown -->
                            <div class="relative">
                                <select name="sort"
                                    class="border rounded-lg px-4 py-2 border-pink-400 text-sm focus:ring-pink-500 focus:border-pink-500"
                                    onchange="this.form.submit()">
                                    <option value="asc" {{ request('sort', 'asc') == 'asc' ? 'selected' : '' }}>Longest
                                    </option>
                                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Oldest
                                    </option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="text-pink-600 uppercase border-b">
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">profile_photo</th>
                            <th class="py-3 px-4">Nama</th>
                            <th class="py-3 px-4">Email</th>
                            <th class="py-3 px-4">Phone</th>
                            <th class="py-3 px-4">Address</th>
                            <th class="py-3 px-4">Created At</th>
                            <th class="py-3 px-4">Role</th>
                            <th class="py-3 px-4 flex items-center justify-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse ($users as $index => $user)
                            <tr class="border-b hover:bg-pink-50">
                                <td class="py-3 px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-4">
                                    @if ($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo"
                                            class="w-10 h-10 rounded-full">
                                    @else
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-circle  text-pink-600 text-[36px]"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">{{ $user->name }}</td>
                                <td class="py-3 px-4">{{ $user->email }}</td>
                                <td class="py-3 px-4">{{ $user->phone }}</td>
                                <td class="py-3 px-4">{{ $user->address }}</td>
                                <td class="py-3 px-4">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="py-3 px-4">{{ ucfirst($user->role) }}</td>
                                <td class="py-3 px-4 flex items-center justify-center space-x-2">
                                    @php
                                        $isSuperAdmin = auth()->user()->email === env('SUPER_ADMIN_EMAIL');
                                    @endphp

                                    @if ($isSuperAdmin && $user->email !== env('SUPER_ADMIN_EMAIL'))
                                        <!-- Tombol ubah role -->
                                        <button
                                            onclick="openRoleChangeModal('{{ route('admin.users.change-role', $user->id) }}')"
                                            data-popover-target="popover-{{ $user->id }}"
                                            class="text-blue-500 hover:text-white border border-blue-500 px-4 py-2 rounded-lg hover:bg-blue-600 font-semibold transition duration-300">
                                            <i class="fa-solid fa-people-arrows text-base"></i>
                                        </button>
                                        <div data-popover id="popover-{{ $user->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block w-30 text-center text-sm text-gray-700 transition-opacity duration-300 bg-blue-600 rounded-lg shadow-md opacity-0">
                                            <div class="px-4 py-3 rounded-t-lg">
                                                <h3 class="text-center font-semibold text-white">Role Change</h3>
                                            </div>
                                        </div>

                                        <!-- Tombol delete -->
                                        <button onclick="openDeleteModal('{{ route('admin.users.destroy', $user->id) }}')"
                                            class="text-red-600 hover:text-red-700 font-semibold">
                                            <i class="fas fa-trash-alt text-lg"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center pt-10">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

                <div class="mt-4">
                    {{ $users->appends(request()->except('page'))->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Role Change -->
    <div id="modal-role-change" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-sm space-y-4 shadow-xl animate-fade-in text-center">
            <div class="text-blue-600 text-3xl">
                <i class="fas fa-user-cog"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Ubah Role Pengguna?</h3>
            <p class="text-sm text-gray-600">Apakah kamu yakin ingin mengubah role pengguna ini? Pastikan perubahan sesuai
                dengan kebijakan.</p>
            <div class="flex justify-center mt-6 space-x-3">
                <button onclick="closeRoleChangeModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-300">
                    Batal
                </button>
                <form id="roleChangeForm" x-data="{ loading: false }" @submit="loading = true" method="POST"
                    action="">
                    @csrf
                    @method('PATCH')
                    <x-spinner-button loading="loading" label="Ubah" loadingLabel="Mengubah..." bgColor="bg-blue-600"
                        hoverColor="hover:bg-blue-700" ringColor="focus:ring-blue-500"
                        icon='<i class="fa-solid fa-people-arrows mr-2"></i>' />
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div id="modal-delete-payment"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-sm space-y-4 shadow-xl animate-fade-in text-center">
            <div class="text-red-600 text-3xl">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Hapus Akun Pengguna?</h3>
            <p class="text-sm text-gray-600">Menghapus pengguna ini akan menghilangkan semua data yang terkait. Apakah anda yakin?</p>
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
        function openRoleChangeModal(actionUrl) {
            document.getElementById('roleChangeForm').action = actionUrl;
            document.getElementById('modal-role-change').classList.remove('hidden');
        }

        function closeRoleChangeModal() {
            document.getElementById('modal-role-change').classList.add('hidden');
        }

        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('modal-delete-payment').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('modal-delete-payment').classList.add('hidden');
        }
    </script>
@endsection
