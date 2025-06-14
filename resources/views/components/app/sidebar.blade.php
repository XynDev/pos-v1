<div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div
        class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
        aria-hidden="true"
        x-cloak
    ></div>

    <!-- Sidebar -->
    <div
        id="sidebar"
        class="flex lg:flex! flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:w-64! shrink-0 bg-white dark:bg-gray-800 p-4 transition-all duration-200 ease-in-out {{ $variant === 'v2' ? 'border-r border-gray-200 dark:border-gray-700/60' : 'rounded-r-2xl shadow-xs' }}"
        :class="sidebarOpen ? 'max-lg:translate-x-0' : 'max-lg:-translate-x-64'"
        @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false"
    >

        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pr-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-gray-500 hover:text-gray-400" @click.stop="sidebarOpen = !sidebarOpen"
                    aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z"/>
                </svg>
            </button>
            <!-- Logo -->
            <a class="block" href="@if(Route::has('dashboard')){{ route('dashboard') }}@endif">
                <svg class="fill-violet-500" xmlns="http://www.w3.org/2000/svg" width="32" height="32">
                    <path
                        d="M31.956 14.8C31.372 6.92 25.08.628 17.2.044V5.76a9.04 9.04 0 0 0 9.04 9.04h5.716ZM14.8 26.24v5.716C6.92 31.372.63 25.08.044 17.2H5.76a9.04 9.04 0 0 1 9.04 9.04Zm11.44-9.04h5.716c-.584 7.88-6.876 14.172-14.756 14.756V26.24a9.04 9.04 0 0 1 9.04-9.04ZM.044 14.8C.63 6.92 6.92.628 14.8.044V5.76a9.04 9.04 0 0 1-9.04 9.04H.044Z"/>
                </svg>
            </a>
        </div>

        <!-- Links -->
        <div class="space-y-8">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                          aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Pages</span>
                </h3>
                <ul class="mt-3">
                    <!-- Dashboard -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(Request::segment(1) === 'dashboard'){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(Request::segment(1) !== 'dashboard'){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif"
                           href="@if(Route::has('dashboard')){{ route('dashboard') }}@else#@endif">
                            <div class="flex items-center">
                                <svg
                                    class="shrink-0 fill-current @if(Request::segment(1) === 'dashboard'){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path
                                        d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z"/>
                                    <path
                                        d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z"/>
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 @if(Request::segment(1) === 'dashboard'){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <!-- Produk -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(Request::segment(1) === 'product'){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif"
                        x-data="{ open: {{ Request::segment(1) === 'product' ? 1 : 0 }} }">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(Request::segment(1) !== 'product'){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif"
                           href="#" @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg
                                        class="shrink-0 fill-current @if(Request::segment(1) === 'product'){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path
                                            d="M9 6.855A3.502 3.502 0 0 0 8 0a3.5 3.5 0 0 0-1 6.855v1.656L5.534 9.65a3.5 3.5 0 1 0 1.229 1.578L8 10.267l1.238.962a3.5 3.5 0 1 0 1.229-1.578L9 8.511V6.855ZM6.5 3.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm4.803 8.095c.005-.005.01-.01.013-.016l.012-.016a1.5 1.5 0 1 1-.025.032ZM3.5 11c.474 0 .897.22 1.171.563l.013.016.013.017A1.5 1.5 0 1 1 3.5 11Z"/>
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Product</span>
                                </div>
                                <!-- Icon -->
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg
                                        class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if(Request::segment(1) === 'product'){{ 'rotate-180' }}@endif"
                                        :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8 mt-1 @if(Request::segment(1) !== 'product'){{ 'hidden' }}@endif"
                                :class="open ? 'block!' : 'hidden'">
                                @can('manage-product-variants')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('products.index')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('products.index')){{ route('products.index') }}@else#@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Products</span>
                                    </a>
                                </li>
                                @endcan
                                @can('manage-categories')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('categories.product')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('categories.product')){{ route('categories.product') }}@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Categories</span>
                                    </a>
                                </li>
                                @endcan
                                @can('manage-brands')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('brands.product')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('brands.product')){{ route('brands.product') }}@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Brands</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <!-- Transaksi -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(Request::segment(1) === 'transactions'){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif"
                        x-data="{ open: {{ Request::segment(1) === 'transactions' ? 1 : 0 }} }">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(Request::segment(1) !== 'transactions'){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif"
                           href="#" @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg
                                        class="shrink-0 fill-current @if(Request::segment(1) === 'transactions'){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path
                                            d="M12 1a1 1 0 1 0-2 0v2a3 3 0 0 0 3 3h2a1 1 0 1 0 0-2h-2a1 1 0 0 1-1-1V1ZM1 10a1 1 0 1 0 0 2h2a1 1 0 0 1 1 1v2a1 1 0 1 0 2 0v-2a3 3 0 0 0-3-3H1ZM5 0a1 1 0 0 1 1 1v2a3 3 0 0 1-3 3H1a1 1 0 0 1 0-2h2a1 1 0 0 0 1-1V1a1 1 0 0 1 1-1ZM12 13a1 1 0 0 1 1-1h2a1 1 0 1 0 0-2h-2a3 3 0 0 0-3 3v2a1 1 0 1 0 2 0v-2Z"/>
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Transactions</span>
                                </div>
                                <!-- Icon -->
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg
                                        class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if(Request::segment(1) === 'transactions'){{ 'rotate-180' }}@endif"
                                        :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8 mt-1 @if(Request::segment(1) !== 'transactions'){{ 'hidden' }}@endif"
                                :class="open ? 'block!' : 'hidden'">
                                @can('create-purchase-orders')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('purchases.orders')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('purchases.orders')){{ route('purchases.orders') }}@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Orders</span>
                                    </a>
                                </li>
                                @endcan
                                @can('access-cashier')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('cashier.index')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('cashier.index')){{ route('cashier.index') }}@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Cashier</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <!-- Kontak -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(Request::segment(1) === 'Contacts'){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif"
                        x-data="{ open: {{ Request::segment(1) === 'Contacts' ? 1 : 0 }} }">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(Request::segment(1) !== 'Contacts'){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif"
                           href="#" @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg
                                        class="shrink-0 fill-current @if(Request::segment(1) === 'Contacts'){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path
                                            d="M6 0a6 6 0 0 0-6 6c0 1.077.304 2.062.78 2.912a1 1 0 1 0 1.745-.976A3.945 3.945 0 0 1 2 6a4 4 0 0 1 4-4c.693 0 1.344.194 1.936.525A1 1 0 1 0 8.912.779 5.944 5.944 0 0 0 6 0Z"/>
                                        <path
                                            d="M10 4a6 6 0 1 0 0 12 6 6 0 0 0 0-12Zm-4 6a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z"/>
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Contacts</span>
                                </div>
                                <!-- Icon -->
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg
                                        class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if(Request::segment(1) === 'Contacts'){{ 'rotate-180' }}@endif"
                                        :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8 mt-1 @if(Request::segment(1) !== 'Contacts'){{ 'hidden' }}@endif"
                                :class="open ? 'block!' : 'hidden'">
                                @can('manage-customers')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('customers.index')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('customers.index')){{ route('customers.index') }}@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Customers</span>
                                    </a>
                                </li>
                                @endcan
                                @can('manage-suppliers')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('management.suppliers')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('management.suppliers')){{ route('management.suppliers') }}@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Suppliers</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <!-- Laporan -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(Request::segment(1) === 'reports'){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif"
                        x-data="{ open: {{ Request::segment(1) === 'reports' ? 1 : 0 }} }">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(Request::segment(1) !== 'reports'){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif"
                           href="#" @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg
                                        class="shrink-0 fill-current @if(Request::segment(1) === 'reports'){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path
                                            d="M6.753 2.659a1 1 0 0 0-1.506-1.317L2.451 4.537l-.744-.744A1 1 0 1 0 .293 5.207l1.5 1.5a1 1 0 0 0 1.46-.048l3.5-4ZM6.753 10.659a1 1 0 1 0-1.506-1.317l-2.796 3.195-.744-.744a1 1 0 0 0-1.414 1.414l1.5 1.5a1 1 0 0 0 1.46-.049l3.5-4ZM8 4.5a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1ZM9 11.5a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z"/>
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Reports</span>
                                </div>
                                <!-- Icon -->
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg
                                        class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if(Request::segment(1) === 'reports'){{ 'rotate-180' }}@endif"
                                        :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8 mt-1 @if(Request::segment(1) !== 'reports'){{ 'hidden' }}@endif"
                                :class="open ? 'block!' : 'hidden'">
                                @can('view-stock-cards')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('reports.stock-card')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('reports.stock-card')){{ route('reports.stock-card') }}@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Stock Card</span>
                                    </a>
                                </li>
                                @endcan
                                @can('view-sales-reports')
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('sales.index')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('sales.index')){{route('sales.index') }}@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Sales</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                    <!-- Pengaturan -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(Request::segment(1) === 'settings'){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif"
                        x-data="{ open: {{ Request::segment(1) === 'settings' ? 1 : 0 }} }">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(Request::segment(1) !== 'settings'){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif"
                           href="#" @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg
                                        class="shrink-0 fill-current @if(Request::segment(1) === 'settings'){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path
                                            d="M10.5 1a3.502 3.502 0 0 1 3.355 2.5H15a1 1 0 1 1 0 2h-1.145a3.502 3.502 0 0 1-6.71 0H1a1 1 0 0 1 0-2h6.145A3.502 3.502 0 0 1 10.5 1ZM9 4.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM5.5 9a3.502 3.502 0 0 1 3.355 2.5H15a1 1 0 1 1 0 2H8.855a3.502 3.502 0 0 1-6.71 0H1a1 1 0 1 1 0-2h1.145A3.502 3.502 0 0 1 5.5 9ZM4 12.5a1.5 1.5 0 1 0 3 0 1.5 1.5 0 0 0-3 0Z"
                                            fill-rule="evenodd"/>
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Settings</span>
                                </div>
                                <!-- Icon -->
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg
                                        class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if(Request::segment(1) === 'settings'){{ 'rotate-180' }}@endif"
                                        :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8 mt-1 @if(Request::segment(1) !== 'settings'){{ 'hidden' }}@endif"
                                :class="open ? 'block!' : 'hidden'">
                                @if(auth()->user()->hasRole('Super Admin'))
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('user.management')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('user.management')){{route('user.management') }}@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">User Management</span>
                                    </a>
                                </li>
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('role.permission.management')){{ 'text-violet-500!' }}@endif"
                                       href="@if(Route::has('role.permission.management')){{ route('role.permission.management') }}@endif">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Role & Permission Management</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
{{--            <!-- More group -->--}}
{{--            <div>--}}
{{--                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">--}}
{{--                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"--}}
{{--                          aria-hidden="true">•••</span>--}}
{{--                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">More</span>--}}
{{--                </h3>--}}
{{--                <ul class="mt-3">--}}
{{--                    <!-- Authentication -->--}}
{{--                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0" x-data="{ open: false }">--}}
{{--                        <a class="block text-gray-800 dark:text-gray-100 truncate transition"--}}
{{--                           :class="open ? '' : 'hover:text-gray-900 dark:hover:text-white'" href="#"--}}
{{--                           @click.prevent="open = !open; sidebarExpanded = true">--}}
{{--                            <div class="flex items-center justify-between">--}}
{{--                                <div class="flex items-center">--}}
{{--                                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500"--}}
{{--                                         xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">--}}
{{--                                        <path--}}
{{--                                            d="M11.442 4.576a1 1 0 1 0-1.634-1.152L4.22 11.35 1.773 8.366A1 1 0 1 0 .227 9.634l3.281 4a1 1 0 0 0 1.59-.058l6.344-9ZM15.817 4.576a1 1 0 1 0-1.634-1.152l-5.609 7.957a1 1 0 0 0-1.347 1.453l.656.8a1 1 0 0 0 1.59-.058l6.344-9Z"/>--}}
{{--                                    </svg>--}}
{{--                                    <span--}}
{{--                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Authentication</span>--}}
{{--                                </div>--}}
{{--                                <!-- Icon -->--}}
{{--                                <div--}}
{{--                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">--}}
{{--                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"--}}
{{--                                         :class="{ 'rotate-180': open }" viewBox="0 0 12 12">--}}
{{--                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>--}}
{{--                                    </svg>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">--}}
{{--                            <ul class="pl-8 mt-1" :class="{ 'hidden': !open }" x-cloak>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <form method="POST" action="{{ route('logout') }}" x-data>--}}
{{--                                        @csrf--}}

{{--                                        <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate"--}}
{{--                                           href="@if(Route::has('logout')){{ route('logout') }}@endif"--}}
{{--                                           click.prevent="$root.submit();">--}}
{{--                                            <span--}}
{{--                                                class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Sign In</span>--}}
{{--                                        </a>--}}
{{--                                    </form>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <form method="POST" action="{{ route('logout') }}" x-data>--}}
{{--                                        @csrf--}}

{{--                                        <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate"--}}
{{--                                           href="@if(Route::has('logout')){{ route('logout') }}@endif"--}}
{{--                                           click.prevent="$root.submit();">--}}
{{--                                            <span--}}
{{--                                                class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Sign Up</span>--}}
{{--                                        </a>--}}
{{--                                    </form>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <form method="POST" action="{{ route('logout') }}" x-data>--}}
{{--                                        @csrf--}}

{{--                                        <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate"--}}
{{--                                           href="@if(Route::has('logout')){{ route('logout') }}@endif"--}}
{{--                                           click.prevent="$root.submit();">--}}
{{--                                            <span--}}
{{--                                                class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Reset Password</span>--}}
{{--                                        </a>--}}
{{--                                    </form>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                    <!-- Onboarding -->--}}
{{--                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0" x-data="{ open: false }">--}}
{{--                        <a class="block text-gray-800 dark:text-gray-100 truncate transition"--}}
{{--                           :class="open ? '' : 'hover:text-gray-900 dark:hover:text-white'" href="#"--}}
{{--                           @click.prevent="open = !open; sidebarExpanded = true">--}}
{{--                            <div class="flex items-center justify-between">--}}
{{--                                <div class="flex items-center">--}}
{{--                                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500"--}}
{{--                                         xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">--}}
{{--                                        <path--}}
{{--                                            d="M6.668.714a1 1 0 0 1-.673 1.244 6.014 6.014 0 0 0-4.037 4.037 1 1 0 1 1-1.916-.571A8.014 8.014 0 0 1 5.425.041a1 1 0 0 1 1.243.673ZM7.71 4.709a3 3 0 1 0 0 6 3 3 0 0 0 0-6ZM9.995.04a1 1 0 1 0-.57 1.918 6.014 6.014 0 0 1 4.036 4.037 1 1 0 0 0 1.917-.571A8.014 8.014 0 0 0 9.995.041ZM14.705 8.75a1 1 0 0 1 .673 1.244 8.014 8.014 0 0 1-5.383 5.384 1 1 0 0 1-.57-1.917 6.014 6.014 0 0 0 4.036-4.037 1 1 0 0 1 1.244-.673ZM1.958 9.424a1 1 0 0 0-1.916.57 8.014 8.014 0 0 0 5.383 5.384 1 1 0 0 0 .57-1.917 6.014 6.014 0 0 1-4.037-4.037Z"/>--}}
{{--                                    </svg>--}}
{{--                                    <span--}}
{{--                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Onboarding</span>--}}
{{--                                </div>--}}
{{--                                <!-- Icon -->--}}
{{--                                <div--}}
{{--                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">--}}
{{--                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"--}}
{{--                                         :class="{ 'rotate-180': open }" viewBox="0 0 12 12">--}}
{{--                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>--}}
{{--                                    </svg>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">--}}
{{--                            <ul class="pl-8 mt-1 @if(Request::segment(1) !== 'onboarding'){{ 'hidden' }}@endif"--}}
{{--                                :class="open ? 'block!' : 'hidden'">--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate"--}}
{{--                                       href="@if(Route::has('onboarding-01')){{ route('onboarding-01') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Step 1</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate"--}}
{{--                                       href="@if(Route::has('onboarding-02')){{ route('onboarding-02') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Step 2</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate"--}}
{{--                                       href="@if(Route::has('onboarding-03')){{ route('onboarding-03') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Step 3</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate"--}}
{{--                                       href="@if(Route::has('onboarding-04')){{ route('onboarding-04') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Step 4</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                    <!-- Components -->--}}
{{--                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if(Request::segment(1) === 'component'){{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif"--}}
{{--                        x-data="{ open: {{ Request::segment(1) === 'component' ? 1 : 0 }} }">--}}
{{--                        <a class="block text-gray-800 dark:text-gray-100 truncate transition"--}}
{{--                           :class="open ? '' : 'hover:text-gray-900 dark:hover:text-white'" href="#"--}}
{{--                           @click.prevent="open = !open; sidebarExpanded = true">--}}
{{--                            <div class="flex items-center justify-between">--}}
{{--                                <div class="flex items-center">--}}
{{--                                    <svg--}}
{{--                                        class="shrink-0 fill-current @if(Request::segment(1) === 'component'){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif"--}}
{{--                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">--}}
{{--                                        <path--}}
{{--                                            d="M.06 10.003a1 1 0 0 1 1.948.455c-.019.08.01.152.078.19l5.83 3.333c.053.03.116.03.168 0l5.83-3.333a.163.163 0 0 0 .078-.188 1 1 0 0 1 1.947-.459 2.161 2.161 0 0 1-1.032 2.384l-5.83 3.331a2.168 2.168 0 0 1-2.154 0l-5.83-3.331a2.162 2.162 0 0 1-1.032-2.382Zm7.856-7.981-5.83 3.332a.17.17 0 0 0 0 .295l5.828 3.33c.054.031.118.031.17.002l5.83-3.333a.17.17 0 0 0 0-.294L8.085 2.023a.172.172 0 0 0-.17-.001ZM9.076.285l5.83 3.332c1.458.833 1.458 2.935 0 3.768l-5.83 3.333c-.667.38-1.485.38-2.153-.001l-5.83-3.332c-1.457-.833-1.457-2.935 0-3.767L6.925.285a2.173 2.173 0 0 1 2.15 0Z"/>--}}
{{--                                    </svg>--}}
{{--                                    <span--}}
{{--                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Components</span>--}}
{{--                                </div>--}}
{{--                                <!-- Icon -->--}}
{{--                                <div--}}
{{--                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">--}}
{{--                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"--}}
{{--                                         :class="{ 'rotate-180': open }" viewBox="0 0 12 12">--}}
{{--                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"/>--}}
{{--                                    </svg>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">--}}
{{--                            <ul class="pl-8 mt-1 @if(Request::segment(1) !== 'component'){{ 'hidden' }}@endif"--}}
{{--                                :class="open ? 'block!' : 'hidden'">--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('button-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('button-page')){{ route('button-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Button</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('form-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('form-page')){{ route('form-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Input Form</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('dropdown-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('dropdown-page')){{ route('dropdown-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dropdown</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('alert-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('alert-page')){{ route('alert-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Alert & Banner</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('modal-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('modal-page')){{ route('modal-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Modal</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('pagination-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('pagination-page')){{ route('pagination-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Pagination</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('tabs-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('tabs-page')){{ route('tabs-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Tabs</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('breadcrumb-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('breadcrumb-page')){{ route('breadcrumb-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Breadcrumb</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('badge-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('badge-page')){{ route('badge-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Badge</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('avatar-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('avatar-page')){{ route('avatar-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Avatar</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('tooltip-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('tooltip-page')){{ route('tooltip-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Tooltip</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('accordion-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('accordion-page')){{ route('accordion-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Accordion</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="mb-1 last:mb-0">--}}
{{--                                    <a class="block text-gray-500/90 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if(Route::is('icons-page')){{ 'text-violet-500!' }}@endif"--}}
{{--                                       href="@if(Route::has('icons-page')){{ route('icons-page') }}@endif">--}}
{{--                                        <span--}}
{{--                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Icons</span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </div>--}}
        </div>

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="w-12 pl-4 pr-3 py-2">
                <button
                    class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition-colors"
                    @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500 sidebar-expanded:rotate-180"
                         xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z"/>
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>
