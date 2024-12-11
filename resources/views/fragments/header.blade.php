<!DOCTYPE html>
<html lang="en">
<body>
<header id="page-header" class="header header-fixed d-flex">
    <div class="container-fluid d-flex align-items-center justify-content-end header-container">
        <div><a href="{{ route('auth.google') }}">Đăng nhập bằng google</a></div>
        <div class="dropdown dropdown-profile">
            <div class="topbar-item d-flex align-items-center dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <span>Hi,</span>
                <span class="header-name" style="text-transform: capitalize">
                    {{ $user()->full_name }}
                </span>
                <span style="padding-bottom: 10px">
                    <img alt="avatar" src="{{ asset('assets/images/default.png') }}" class="avatar"/>
                </span>
            </div>
            <ul class="dropdown-menu dropdown-menu-profile" aria-labelledby="dropdownMenuButton">
                <li class="dropdown-item d-flex justify-content-center align-items-center flex-column">
                    <img alt="avatar" src="https://picsum.photos/200/300" class="avatar-thumb"/>
                    <p class="dropdown-item-name mt-2 text-center" style="text-transform: capitalize">
                        full_name{{-- {{ Auth::user()->full_name }} --}}
                    </p>
                </li>
                <div class="dropdown-divider"></div>
                <li><a class="dropdown-item" href="/profile/info/">Profile</a></li>
                <li><a class="dropdown-item" href="/logout">Logout</a></li>
            </ul>
        </div>
    </div>
</header>
</body>
</html>
