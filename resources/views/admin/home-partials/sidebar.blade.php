<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-pink-100 shadow-sm sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto">
        <ul class="space-y-1 font-medium">

            <!-- Dashboard -->
            <li class="mt-2">
                <a href="{{ route('admin.dashboard.index') }}"
                    class="flex items-center p-3 rounded-lg group transition-all duration-200
                        {{ request()->is('admin/dashboard') ? 'text-pink-700 bg-pink-50' : 'text-pink-500 hover:text-pink-700 hover:bg-pink-50' }}">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="fas fa-tachometer-alt text-sm"></i>
                    </div>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            <!-- Products Dropdown -->
            @php $isProductActive = request()->is('admin/product') || request()->is('admin/category'); @endphp
            <li>
                <button type="button"
                    class="flex items-center w-full p-3 rounded-lg transition-all duration-200 group
                        {{ $isProductActive ? 'text-pink-700 bg-pink-50' : 'text-pink-500 hover:text-pink-700 hover:bg-pink-50' }}"
                    aria-controls="dropdown-products" data-collapse-toggle="dropdown-products">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="fas fa-utensils text-sm"></i>
                    </div>
                    <span class="flex-1 ms-3 text-left whitespace-nowrap">Products</span>
                    <i
                        class="fas fa-chevron-down text-xs text-pink-400 transition-transform duration-200
                        {{ $isProductActive ? 'rotate-180' : '' }}"></i>
                </button>
                <ul id="dropdown-products" class="py-2 space-y-1 pl-4 {{ $isProductActive ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ route('admin.category.index') }}"
                            class="flex items-center p-2 rounded-lg transition-all duration-200 pl-6
                                {{ request()->is('admin/category') ? 'text-pink-700 bg-pink-50' : 'text-pink-600 hover:bg-pink-50' }}">
                            <div class="w-1 h-1 rounded-full bg-pink-400 mr-3"></div>
                            <span class="text-sm">Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.product.index') }}"
                            class="flex items-center p-2 rounded-lg transition-all duration-200 pl-6
                                {{ request()->is('admin/product') ? 'text-pink-700 bg-pink-50' : 'text-pink-600 hover:bg-pink-50' }}">
                            <div class="w-1 h-1 rounded-full bg-pink-400 mr-3"></div>
                            <span class="text-sm">All Products</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Orders -->
            <li>
                <a href="{{ route('admin.order.index') }}"
                    class="flex items-center p-3 rounded-lg group transition-all duration-200
                                    {{ request()->is('admin/order') ? 'text-pink-700 bg-pink-50' : 'text-pink-500 hover:text-pink-700 hover:bg-pink-50' }}">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-sm"></i>
                    </div>
                    <span class="flex-1 ms-3 whitespace-nowrap">Orders</span>
                    @if ($unseenOrders > 0)
                        <span class="ml-2 bg-pink-500 text-white rounded-full text-xs px-2 py-0.5">
                            {{ $unseenOrders }}
                        </span>
                    @endif
                </a>
            </li>

            <!-- Report -->
            <li>
                <a href="{{ route('admin.report.index') }}"
                    class="flex items-center p-3 rounded-lg group transition-all duration-200
                                    {{ request()->is('admin/report') ? 'text-pink-700 bg-pink-50' : 'text-pink-500 hover:text-pink-700 hover:bg-pink-50' }}">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="fas fa-chart-bar text-sm"></i>
                    </div>
                    <span class="flex-1 ms-3 whitespace-nowrap">Reports</span>
                </a>
            </li>

            <!-- Chat -->
            <li>
                <a href="{{ route('admin.chat') }}"
                    class="flex items-center p-3 rounded-lg group transition-all duration-200
                                    {{ request()->is('admin/chat') ? 'text-pink-700 bg-pink-50' : 'text-pink-500 hover:text-pink-700 hover:bg-pink-50' }}">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="fa-solid fa-comment-dots text-sm"></i>
                    </div>
                    <span class="flex-1 ms-3 whitespace-nowrap">Chat</span>
                    @if (!empty($unreadChatCount) && $unreadChatCount > 0)
                        <span class="ml-2 bg-pink-500 text-white rounded-full text-xs px-2 py-0.5">
                            {{ $unreadChatCount }}
                        </span>
                    @endif
                </a>
            </li>

            <!-- Vouchers -->
            <li>
                <a href="{{ route('admin.voucher.index') }}"
                    class="flex items-center p-3 rounded-lg group transition-all duration-200
                        {{ request()->is('admin/voucher') ? 'text-pink-700 bg-pink-50' : 'text-pink-500 hover:text-pink-700 hover:bg-pink-50' }}">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="fa-solid fa-tags text-sm"></i>
                    </div>
                    <span class="ms-3 whitespace-nowrap">Vouchers</span>
                </a>
            </li>

            <!-- Payments -->
            <li>
                <a href="{{ route('admin.payment-methods.index') }}"
                    class="flex items-center p-3 rounded-lg group transition-all duration-200
                        {{ request()->is('admin/payment') ? 'text-pink-700 bg-pink-50' : 'text-pink-500 hover:text-pink-700 hover:bg-pink-50' }}">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="fas fa-credit-card text-sm"></i>
                    </div>
                    <span class="ms-3 whitespace-nowrap">Payments</span>
                </a>
            </li>

            <!-- Users -->
            <li>
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center p-3 rounded-lg group transition-all duration-200
                        {{ request()->is('admin/users') ? 'text-pink-700 bg-pink-50' : 'text-pink-500 hover:text-pink-700 hover:bg-pink-50' }}">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    <span class="ms-3 whitespace-nowrap">Users</span>
                </a>
            </li>

        </ul>
    </div>
</aside>

<!-- Dropdown Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownButtons = document.querySelectorAll('[data-collapse-toggle]');

        // Restore dropdown state from localStorage
        dropdownButtons.forEach(button => {
            const targetId = button.getAttribute('aria-controls');
            const target = document.getElementById(targetId);
            const icon = button.querySelector('.fa-chevron-down');

            if (localStorage.getItem(targetId) === 'open') {
                target.classList.remove('hidden');
                icon.classList.add('rotate-180');
            }

            button.addEventListener('click', function(e) {
                e.stopPropagation();

                target.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');

                // Save state to localStorage
                if (target.classList.contains('hidden')) {
                    localStorage.setItem(targetId, 'closed');
                } else {
                    localStorage.setItem(targetId, 'open');
                }
            });
        });
    });
</script>
