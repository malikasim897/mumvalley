<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto"><a class="navbar-brand" href="{{ route('dashboard') }}">
                    <span class="brand-logo">
                        {{-- <svg viewbox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                            <defs>
                                <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%"
                                    y2="89.4879456%">
                                    <stop stop-color="#000000" offset="0%"></stop>
                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                </lineargradient>
                                <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%"
                                    y2="100%">
                                    <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                    <stop stop-color="#FFFFFF" offset="100%"></stop>
                                </lineargradient>
                            </defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                                    <g id="Group" transform="translate(400.000000, 178.000000)">
                                        <path class="text-primary" id="Path"
                                            d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                                            style="fill:currentColor"></path>
                                        <path id="Path1"
                                            d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                                            fill="url(#linearGradient-1)" opacity="0.2"></path>
                                        <polygon id="Path-2" fill="#000000" opacity="0.049999997"
                                            points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325">
                                        </polygon>
                                        <polygon id="Path-21" fill="#000000" opacity="0.099999994"
                                            points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338">
                                        </polygon>
                                        <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994"
                                            points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                                    </g>
                                </g>
                            </g>
                        </svg> --}}
                        <img src="{{ asset('images/logo/logo12.png') }}" alt="logo image" style="max-width: 170px !important; margin-top:-20px; margin-left:8px;">
                    </span>
                    {{-- <h2 class="brand-text">Salar Goods</h2> --}}
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="{{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('dashboard') }}"><i
                        data-feather="home"></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Dashboard</span></a></li>
            @canany(['product.view', 'product.create', 'product.edit', 'product.delete'])
                @can('product.view')
                    <li class="{{ in_array(Route::currentRouteName(), ['products.index', 'product.checkin', 'product.orders', 'product.order.units', 'products.edit']) ? 'active' : '' }} nav-item {{ (auth()->user()->hasRole('user') && auth()->user()->status == 0) ? 'disabled' : '' }}"><a
                            class="d-flex align-items-center" href="{{ route('products.index') }}"><i
                                data-feather="package"></i><span class="menu-title text-truncate"
                                data-i18n="Products">Products</span></a>
                    </li>
                @endcan
            @endcanany
            @canany(['order.view', 'order.create', 'order.edit', 'order.delete'])
                @can('order.view')
                    <li class="{{ in_array(Route::currentRouteName(), ['orders.index', 'product.order.details']) ? 'active' : '' }} nav-item"><a
                            class="d-flex align-items-center" href="{{ route('orders.index') }}"><i
                                data-feather="shopping-bag"></i><span class="menu-title text-truncate"
                                data-i18n="Orders">Orders</span></a>
                    </li>
                @endcan
            @endcanany
            {{-- @canany(['order.view', 'order.create', 'order.edit', 'order.delete'])
                <li class="nav-item {{ (auth()->user()->hasRole('user') && auth()->user()->status == 0) ? 'disabled' : '' }}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="shopping-bag"></i>
                        <span class="menu-title text-truncate">Orders</span>
                    </a>
                    <ul class="menu-content">
                        @can('order.view')
                            <li class="{{ in_array(Route::currentRouteName(), ['orders.pending', 'product.order.details']) ? 'active' : '' }} nav-item">
                                <a class="d-flex align-items-center" href="{{ route('orders.pending') }}">
                                    <i data-feather="clock" style="width: 15px; height: 15px;"></i>
                                    <span class="menu-title text-truncate">Pending Orders</span>
                                    @if ($pendingOrderCount > 0)
                                        <span class="badge bg-danger" style="margin-left: 5px;">{{ $pendingOrderCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @endcan
                        @can('order.view')
                            <li class="{{ in_array(Route::currentRouteName(), ['orders.shipped']) ? 'active' : '' }} nav-item">
                                <a class="d-flex align-items-center" href="{{ route('orders.shipped') }}">
                                    <i data-feather="truck" style="width: 15px; height: 15px;"></i>
                                    <span class="menu-title text-truncate">Shipped Orders</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany --}}

            {{-- @canany(['rate.view'])
                <li class="nav-item"><a class="d-flex align-items-center" href="#"><i
                            data-feather="dollar-sign"></i><span class="menu-title text-truncate">Rates</span></a>
                    <ul class="menu-content">
                        @can('rate.view')
                            <li class="{{ in_array(Route::currentRouteName(), ['rates.index']) ? 'active' : '' }} nav-item"><a
                                    class="d-flex align-items-center" href="{{ route('rates.index') }}">
                                    <i data-feather="dollar-sign"></i><span class="menu-title text-truncate">Shipping
                                        Rates</span></a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany --}}
            @canany(['invoice.view'])
                @can('invoice.view')
                    <li class="{{ in_array(Route::currentRouteName(), ['invoices.index', 'payment-invoices.orders.index', 'payment-invoices.invoice.edit', 'payment-invoices.invoice.show', 'payment-invoices.invoice.checkout.index', 'payment-invoices.index']) ? 'active' : '' }} nav-item {{ (auth()->user()->hasRole('user') && auth()->user()->status == 0) ? 'disabled' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('invoices.index') }}">
                            <i data-feather="dollar-sign"></i><span class="menu-title text-truncate">Invoices</span></a>
                    </li>
                @endcan
            @endcanany
            <li class="{{ in_array(Route::currentRouteName(), ['reports.index']) ? 'active' : '' }} nav-item {{ (auth()->user()->hasRole('user') && auth()->user()->status == 0) ? 'disabled' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('reports.index') }}">
                    <i data-feather="file-text"></i><span class="menu-title text-truncate">Reports</span></a>
            </li>
            {{-- @canany(['storage_invoice.view', 'storage_invoice.create', 'storage_invoice.edit', 'storage_invoice.delete'])
                @can('storage_invoice.view')
                    <li class="{{ Route::currentRouteName() == 'storage-invoices.index' ? 'active' : '' }} nav-item {{ (auth()->user()->hasRole('user') && auth()->user()->status == 0) ? 'disabled' : '' }}"><a
                            class="d-flex align-items-center" href="{{ route('storage-invoices.index') }}"><i
                                data-feather="archive"></i><span class="menu-title text-truncate"
                                data-i18n="Storage Invoices">Storage Invoices</span></a>
                    </li>
                @endcan
            @endcanany
            @canany(['transaction.view', 'transaction.create', 'transaction.edit', 'transaction.delete'])
                @can('transaction.view')
                    <li class="{{ Route::currentRouteName() == 'transactions.index' ? 'active' : '' }} nav-item"><a
                            class="d-flex align-items-center" href="{{ route('transactions.index') }}"><i
                                data-feather="credit-card"></i><span class="menu-title text-truncate"
                                data-i18n="Transactions">Transactions</span></a>
                    </li>
                @endcan
            @endcanany --}}

            @canany(['user.view', 'user.create', 'user.edit', 'user.delete'])
                @can('user.view')
                    <li class="{{ in_array(Route::currentRouteName(), ['users.index', 'users.create']) ? 'active' : '' }} nav-item"><a
                            class="d-flex align-items-center" href="{{ route('users.index') }}"><i
                                data-feather="users"></i><span class="menu-title text-truncate"
                                data-i18n="Users">Customers</span></a>
                    </li>
                @endcan
                {{-- <li class="nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="users"></i><span
                            class="menu-title text-truncate" data-i18n="Users">Users</span></a>
                    <ul class="menu-content">
                        @can('user.view')
                            <li class="{{ Route::currentRouteName() == 'user.index' ? 'active' : '' }}"><a
                                    class="d-flex align-items-center" href="{{ route('user.index') }}"><i
                                        data-feather="circle"></i><span class="menu-title text-truncate"
                                        data-i18n="View">List</span></a>
                            </li>
                        @endcan
                        @can('role.create')
                            <li class="{{ Route::currentRouteName() == 'user.create' ? 'active' : '' }}"><a
                                    class="d-flex align-items-center" href="{{ route('user.create') }}"><i
                                        data-feather="user"></i><span class="menu-title text-truncate"
                                        data-i18n="View">Create</span></a>
                            </li>
                        @endcan
                    </ul>
                </li> --}}
            @endcanany
            @canany(['role.view', 'role.create', 'role.edit', 'role.delete'])
                @can('role.view')
                    <li class="{{ in_array(Route::currentRouteName(), ['roles.index', 'roles.edit', 'roles.edit.permissions']) ? 'active' : '' }} nav-item"><a
                            class="d-flex align-items-center" href="{{ route('roles.index') }}"><i
                                data-feather="grid"></i><span class="menu-item text-truncate" data-i18n="Roles">Roles</span></a>
                    </li>
                @endcan
                {{-- <li class="nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="grid"></i><span
                            class="menu-title text-truncate" data-i18n="Roles">Roles</span></a>
                    <ul class="menu-content">
                        @can('role.view')
                            <li class="{{ Route::currentRouteName() == 'roles.index' ? 'active' : '' }}"><a
                                    class="d-flex align-items-center" href="{{ route('roles.index') }}"><i
                                        data-feather="circle"></i><span class="menu-item text-truncate"
                                        data-i18n="Basic">List</span></a>
                            </li>
                        @endcan
                        @can('role.create')
                            <li class="{{ Route::currentRouteName() == 'roles.create' ? 'active' : '' }}"><a
                                    class="d-flex align-items-center" href="{{ route('roles.create') }}"><i
                                        data-feather="circle"></i><span class="menu-item text-truncate"
                                        data-i18n="Advanced">create</span></a>
                            </li>
                        @endcan
                    </ul>
                </li> --}}
            @endcanany
            @canany(['profile.view', 'profile.create', 'profile.edit', 'profile.delete'])
                @can('profile.view')
                    <li class="{{ Route::currentRouteName() == 'profile.edit' ? 'active' : '' }} nav-item"><a
                            class="d-flex align-items-center" href="{{ route('profile.edit') }}"><i
                                data-feather="user"></i><span class="menu-item text-truncate"
                                data-i18n="Profile">Profile</span></a>
                    </li>
                @endcan
            @endcanany
            @canany(['setting.view', 'setting.create', 'setting.edit', 'setting.delete'])
                @can('setting.view')
                    <li class="{{ Route::currentRouteName() == 'settings.index' ? 'active' : '' }} nav-item"><a
                            class="d-flex align-items-center" href="{{ route('settings.index') }}"><i
                                data-feather="settings"></i><span class="menu-item text-truncate"
                                data-i18n="Settings">Settings</span></a>
                    </li>
                @endcan
            @endcanany
            {{-- @canany(['address.view', 'address.create', 'address.edit', 'address.delete'])
                @can('address.view')
                    <li class="{{ Route::currentRouteName() == 'addresses.index' ? 'active' : '' }} nav-item"><a
                            class="d-flex align-items-center" href="{{ route('addresses.index') }}"><i
                                data-feather="map-pin"></i><span class="menu-item text-truncate"
                                data-i18n="Addresses">Addresses</span></a>
                    </li>
                @endcan
            @endcanany
            <li class="nav-item"><a class="d-flex align-items-center" target="__blank"
                    href="https://documenter.getpostman.com/view/30539962/2s9YXcd52P#a0630940-ce90-4508-9a29-b1f9f9310f75"><i
                        data-feather="clipboard"></i><span class="menu-item text-truncate">API Docs</span></a>
            </li> --}}
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
