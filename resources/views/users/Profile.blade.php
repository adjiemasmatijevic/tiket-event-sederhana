@extends('templates.user')

@section('app_name', config('app.name'))
@section('title', 'Profile')

@section('content')
<div class="container py-4">
 @if (session('success'))
        <div class="mb-3 fs-3 alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-3 fs-3 alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <style>
        .profile-container {
            position: relative;
            width: 100px;
            height: 100px;
            cursor: pointer;
        }

        .profile-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 10px;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s;
            text-align: center;
        }

        .profile-container:hover .profile-overlay {
            opacity: 1;
        }

        .menu-card {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            background: #fff;
            padding: 10px 15px;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            height: 50px;
            transition: box-shadow 0.2s;
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .menu-text {
            font-weight: 500;
            font-size: 14px;
        }

        @media (max-width: 576px) {
            .menu-card {
                width: 100%;
                padding: 10px;
            }
            .menu-icon {
                margin-right: 10px;
            }
        }
    </style>

    <div class="d-flex align-items-center mb-4">
        <div class="profile-container media media-60 me-3 rounded-circle" onclick="document.getElementById('photoInput').click();">
            <img src="{{ $user->profile_picture ? '/storage/profile_pictures/' . $user->profile_picture . '.webp' : '/assets/avatars/default.webp' }}" alt="Profile Photo" loading="lazy">
            <div class="profile-overlay">Change</div>
        </div>

        <form id="photoForm" action="{{ route('update.photo') }}" method="POST" enctype="multipart/form-data" style="display: none;">
            @csrf
            <input type="file" name="photo" id="photoInput" accept=".jpg,.jpeg,.png,image/jpeg,image/png" onchange="document.getElementById('photoForm').submit();">
        </form>

        <div>
            <h4 class="mb-0">{{ $user->name }}</h4>
            <small>{{ $user->email }}</small>
        </div>

        <a href="{{ route('customers.editprofile') }}" class="edit-profile ms-auto">
            <i class="icon feather icon-edit-2" style="font-size: 25px"></i>
        </a>
    </div>

    <div class="content-box mt-4 mb-4">
        <ul class="row g-2">
            <li class="col-6">
                <a href="order.html" class="menu-card">
                    <div class="menu-icon text-primary">
                        <i class="icon feather icon-shopping-cart" style="font-size: 20px;"></i>
                    </div>
                    <span class="menu-text text-secondary">Cart</span>
                </a>
            </li>
            <li class="col-6">
                <a href="https://wa.me/62895378168939?text=Hallo%20spasi%20tiket" class="menu-card">
                    <div class="menu-icon text-primary">
                        <i class="icon feather icon-headphones" style="font-size: 20px;"></i>
                    </div>
                    <span class="menu-text text-secondary">Help Center</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="title-bar mt-0">
        <h6 class="title mb-0 font-w700">Account Settings</h6>
    </div>

    <div class="dz-list style-1">
        <ul>
            <li>
                <a href="{{ route('customers.editprofile') }}" class="item-content item-link">
                    <div class="dz-icon">
                        <i class="icon feather icon-user"></i>
                    </div>
                    <div class="dz-inner">
                        <span class="title">Edit Profile</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('customers.changepassword') }}" class="item-content item-link">
                    <div class="dz-icon">
                        <i class="icon feather icon-lock"></i>
                    </div>
                    <div class="dz-inner">
                        <span class="title">Change Password</span>
                    </div>
                </a>
            </li>
            <li>
                <a href="{{ route('logout') }}" class="item-content item-link" id="logout-button">
                    <div class="dz-icon">
                        <i class="icon feather icon-log-out"></i>
                    </div>
                    <div class="dz-inner">
                        <span class="title">Log Out</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace()
</script>

<script>
    document.getElementById('logout-button').addEventListener('click', function(e) {
        e.preventDefault()
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'rgb(255, 0, 0)',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Logout',
            cancelButtonText: 'Cnacel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = this.href;
            }
        })
    });
</script>
@endsection
