<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/soft-ui-dashboard/pages/dashboard.html "
            target="_blank">
            <img src="{{ asset('/assets/assets/img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">La Tenda</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center fas fa-gauge">
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            @if (Auth::user()->role == 'super_admin')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard/users') ? 'active' : '' }}"
                        href="{{ route('users.index') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center fas fa-users">
                        </div>
                        <span class="nav-link-text ms-1">User Management</span>
                    </a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="{{ asset('/assets/pages/dashboard.html') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center fas fa-list">
                    </div>
                    <span class="nav-link-text ms-1">Category Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ asset('/assets/pages/dashboard.html') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center fas fa-burger">
                    </div>
                    <span class="nav-link-text ms-1">Product Management</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
