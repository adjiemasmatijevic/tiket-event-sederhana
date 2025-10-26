<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('app_name') - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, minimal-ui, viewport-fit=cover">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/logo.webp" />
    <link rel="manifest" href="/manifest.json" />
    <link href="/user/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/user/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" />
    <link rel="stylesheet" href="/user/vendor/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="/user/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@100;200;300;400;500;600;700;800;900;1000&family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet" />

    <script src="/user/js/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
</head>

<body data-theme-color="color-blue">
    <div class="page-wrapper">
        <div id="preloader">
            <div class="loader">
                <div class="load-circle">
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <header class="header shadow header-fixed border-0">
            <div class="container">
                <div class="header-content">
                    <div class="left-content">
                        <a href="javascript:void(0);" class="menu-toggler">
                            <i class="icon feather icon-menu"></i>
                        </a>
                    </div>
                    <div class="mid-content header-logo p-2">
                        <a href="{{ route('dashboard') }}">
                            <img class="logo app-logo" src="/assets/images/logo.webp" alt="logo" />
                        </a>
                    </div>
                    <div class="right-content">
                        <a href="#" class="search-icon">
                            <i class="icon feather icon-bell"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="dark-overlay"></div>
        <div class="sidebar" style="background-image: url('/user/images/background/bg3.png')">
            <a href="profile.html" class="author-box">
                @if(Auth::check())
                <div class="dz-media">
                    <img src="{{ Auth::user()->profile_picture ? '/storage/profile_pictures/' . Auth::user()->profile_picture . '.webp' : '/assets/avatars/default.webp' }}" alt="author-image" />
                </div>
                <div class="dz-info">
                    <h5 class="name">{{ Auth::user()->name }}</h5>
                    <span>{{ Auth::user()->email }}</span>
                </div>
                @else
                <div class="dz-media">
                    <img src="/assets/avatars/default.webp" alt="author-image" />
                </div>
                <div class="dz-info">
                    <h5 class="name">Guest</h5>
                </div>
                @endif
            </a>
            <ul class="nav navbar-nav">
                <li>
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <span class="dz-icon">
                            <i class="icon feather icon-home"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('tickets.my_tickets') ? 'active' : '' }}" href="{{ route('tickets.my_tickets') }}">
                        <span class="dz-icon">
                            <i class="icon feather icon-sidebar"></i>
                        </span>
                        <span>Tickets</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('cart') ? 'active' : '' }}" href="{{ route('cart') }}">
                        <span class="dz-icon">
                            <i class="icon feather icon-shopping-cart"></i>
                        </span>
                        <span>Cart</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('transactions') ? 'active' : '' }}" href="{{ route('transactions') }}">
                        <span class="dz-icon">
                            <i class="icon feather icon-list"></i>
                        </span>
                        <span>Transactions</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                        <span class="dz-icon">
                            <i class="icon feather icon-user"></i>
                        </span>
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('logout') }}" id="logout-link">
                        <span class="dz-icon">
                            <i class="icon feather icon-log-out"></i>
                        </span>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-bottom">
                <div class="app-info">
                    <h6 class="name">@yield('app_name')</h6>
                    <span class="ver-info">App Version 1.0</span>
                </div>
            </div>
        </div>
        <div class="page-content space-top p-b80">
            @yield('content')
        </div>
        <div class="menubar-area footer-fixed rounded-0">
            <div class="toolbar-inner menubar-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="icon feather icon-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('tickets.my_tickets') }}" class="nav-link {{ request()->routeIs('tickets.my_tickets') ? 'active' : '' }}">
                    <i class="icon feather icon-sidebar"></i>
                    <span>Tickets</span>
                </a>
                <a href="{{ route('cart') }}" class="nav-link {{ request()->routeIs('cart') ? 'active' : '' }}">
                    <i class="icon feather icon-shopping-cart"></i>
                    <span>Cart</span>
                </a>
                <a href="{{ route('transactions') }}" class="nav-link {{ request()->routeIs('transactions') ? 'active' : '' }}">
                    <i class="icon feather icon-list"></i>
                    <span>Transactions</span>
                </a>
                <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <i class="icon feather icon-user"></i>
                    <span>Profile</span>
                </a>
            </div>
        </div>
    </div>
    <script src="/user/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/user/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/user/vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
    <script src="/user/js/dz.carousel.js"></script>
    <script src="/user/js/settings.js"></script>
    <script src="/user/js/custom.js"></script>
    <script src="/index.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('logout-link').addEventListener('click', function(event) {
            document.querySelector('.sidebar').classList.remove('show');
            document.querySelector('.dark-overlay').classList.remove('active');

            event.preventDefault();
            const url = this.href;

            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'rgb(255, 0, 0)',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("form").forEach(function(form) {
                form.addEventListener("submit", function() {
                    let btn = form.querySelector("[type=submit]");
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    `;
                    }
                });
            });
        });
    </script>
</body>

</html>