<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="/assets/images/logo.webp" type="image/png">
    <title>@yield('app_name') - @yield('title')</title>
    <link rel="stylesheet" href="/css/simplebar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/feather.css">
    <link rel="stylesheet" href="/css/select2.css">
    <link rel="stylesheet" href="/css/dropzone.css">
    <link rel="stylesheet" href="/css/uppy.min.css">
    <link rel="stylesheet" href="/css/jquery.steps.css">
    <link rel="stylesheet" href="/css/jquery.timepicker.css">
    <link rel="stylesheet" href="/css/quill.snow.css">
    <link rel="stylesheet" href="/css/daterangepicker.css">
    <link rel="stylesheet" href="/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="/css/daterangepicker.css">
    <link rel="stylesheet" href="/js/ckeditor/styles.js">
    <link rel="stylesheet" href="/css/app-light.css" id="lightTheme">
    <link rel="stylesheet" href="/css/app-dark.css" id="darkTheme" disabled>
    <style>
        .cke_notifications_area {
            display: none !important;
        }
    </style>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/moment.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/simplebar.min.js"></script>
    <script src='/js/daterangepicker.js'></script>
    <script src='/js/jquery.stickOnScroll.js'></script>
    <script src="/js/tinycolor-min.js"></script>
    <script src="/js/config.js"></script>
    <script src="/js/d3.min.js"></script>
    <script src="/js/topojson.min.js"></script>
    <script src="/js/datamaps.all.min.js"></script>
    <script src="/js/datamaps-zoomto.js"></script>
    <script src="/js/datamaps.custom.js"></script>
    <script src="/js/Chart.min.js"></script>
    <script src="/js/palette.js"></script>
    <script src="/js/gauge.min.js"></script>
    <script src="/js/jquery.sparkline.min.js"></script>
    <script src="/js/apexcharts.min.js"></script>
    <script src="/js/apexcharts.custom.js"></script>
    <script src='/js/jquery.mask.min.js'></script>
    <script src='/js/select2.min.js'></script>
    <script src='/js/jquery.steps.min.js'></script>
    <script src='/js/jquery.validate.min.js'></script>
    <script src='/js/jquery.timepicker.js'></script>
    <script src='/js/dropzone.min.js'></script>
    <script src='/js/uppy.min.js'></script>
    <script src='/js/quill.min.js'></script>
    <script src='js/jquery.dataTables.min.js'></script>
    <script src='js/dataTables.bootstrap4.min.js'></script>
    <script src="/js/ckeditor/ckeditor.js"></script>
    <script src="/js/apps.js"></script>
</head>

<body class="vertical bg-light">
    <div class="wrapper">
        <nav class="topnav navbar navbar-light">
            <div class="d-none d-lg-block">
            </div>

            <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar d-lg-none">
                <i class="fe fe-menu navbar-toggler-icon"></i>
            </button>

            <ul class="nav">
                <li class="nav-item nav-notif">
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="avatar avatar-sm mt-2">
                            <img src="{{ Auth::user()->profile_picture ? '/storage/profile_pictures/' . Auth::user()->profile_picture . '.webp' : '/assets/avatars/default.webp' }}" alt="profile image"
                                class="avatar-img"
                                style="width:40px;height:40px;border-radius:50%;object-fit:cover;"
                                loading="lazy" />
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                        <button class="dropdown-item" type="button" data-toggle="modal" data-target="#logoutModal">Logout</button>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
            <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
                <i class="fe fe-x"><span class="sr-only"></span></i>
            </a>
            <nav class="vertnav navbar navbar-light">
                <!-- nav bar -->
                <div class="w-100 mb-2 d-flex">
                    <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ route('dashboard') }}">
                        <img src="/assets/images/logo.webp" class="w-50 mb-3" alt="logo">
                    </a>
                </div>
                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fe fe-home fe-16"></i>
                            <span class="ml-3 item-text">Home</span>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a class="nav-link d-flex align-items-center {{ Route::currentRouteName() == 'dashboard' ? 'bg-primary rounded text-white font-weight-bold' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fe fe-airplay fe-16"></i>
                            <span class="ml-3 item-text">Dashboard</span>
                        </a>
                    </li>
                </ul>
                @if(Auth::user()->role === 'admin')
                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a class="nav-link d-flex align-items-center {{ Route::currentRouteName() == 'events' ? 'bg-primary rounded text-white font-weight-bold' : '' }}" href="{{ route('events') }}">
                            <i class="fe fe-calendar fe-16"></i>
                            <span class="ml-3 item-text">Events</span>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a class="nav-link d-flex align-items-center {{ Route::currentRouteName() == 'tickets' ? 'bg-primary rounded text-white font-weight-bold' : '' }}" href="{{ route('tickets') }}">
                            <i class="fe fe-credit-card fe-16"></i>
                            <span class="ml-3 item-text">Tickets</span>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a class="nav-link d-flex align-items-center {{ Route::currentRouteName() == 'ticket_ots' ? 'bg-primary rounded text-white font-weight-bold' : '' }}" href="{{ route('ticket_ots') }}">
                            <i class="fe fe-credit-card fe-16"></i>
                            <span class="ml-3 item-text">Ticket OTS</span>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a class="nav-link d-flex align-items-center {{ Route::currentRouteName() == 'admin.cart' ? 'bg-primary rounded text-white font-weight-bold' : '' }}" href="{{ route('admin.cart') }}">
                            <i class="fe fe-bar-chart-2 fe-16"></i>
                            <span class="ml-3 item-text">Cart</span>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a class="nav-link d-flex align-items-center {{ Route::currentRouteName() == 'trx' ? 'bg-primary rounded text-white font-weight-bold' : '' }}" href="{{ route('trx') }}">
                            <i class="fe fe-bar-chart fe-16"></i>
                            <span class="ml-3 item-text">Transactions</span>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a class="nav-link d-flex align-items-center {{ Route::currentRouteName() == 'users.management' ? 'bg-primary rounded text-white font-weight-bold' : '' }}" href="{{ route('users.management') }}">
                            <i class="fe fe-users fe-16"></i>
                            <span class="ml-3 item-text">Users</span>
                        </a>
                    </li>
                </ul>
                @endif

                @if(Auth::user()->role === 'checker')
                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a class="nav-link d-flex align-items-center {{ Route::currentRouteName() == 'gate-check' ? 'bg-primary rounded text-white font-weight-bold' : '' }}" href="{{ route('gate-check') }}">
                            <i class="fe fe-check-circle fe-16"></i>
                            <span class="ml-3 item-text">Gate Check</span>
                        </a>
                    </li>
                </ul>
                @endif

                <ul class="navbar-nav flex-fill w-100 mb-2">
                    <li class="nav-item w-100">
                        <a class="nav-link d-flex align-items-center" href="{{ route('logout') }}">
                            <i class="fe fe-log-out fe-16"></i>
                            <span class="ml-3 item-text">Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <main role="main" class="main-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        @yield('content')
                    </div> <!-- .col-12 -->
                </div> <!-- .row -->
            </div> <!-- .container-fluid -->

            <!-- Modal Logout -->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-danger" id="exampleModalLabel">Logout</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">x</span>
                            </button>
                        </div>
                        <div class="modal-body">Are you sure you want to logout?</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </main> <!-- main -->
    </div> <!-- .wrapper -->

    <script>
        Chart.defaults.global.defaultFontFamily = base.defaultFontFamily;
        Chart.defaults.global.defaultFontColor = colors.mutedColor;
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
    <script>
        $(document).ready(function() {
            $(".collapseSidebar").on("click", function(e) {
                e.preventDefault();
                $("body").toggleClass("collapsed");
            });
        });
    </script>
</body>

</html>