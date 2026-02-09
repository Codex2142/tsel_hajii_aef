<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" data-bs-toggle="dropdown">
    <i class="align-middle" data-feather="settings"></i>
    </a>

                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" data-bs-toggle="dropdown">
                    @if (Auth::user()->photo)
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile Photo" class="img-fluid" style="max-width: 25px; max-height: 25px; object-fit: cover;">
                    @else
                        <img src="https://placehold.co/25?text={{ strtoupper(substr(Auth::user()->name, 0, 1)) }}" alt="Profile Photo" class="img-fluid" style="max-width: 25px; max-height: 25px; object-fit: cover;">
                    @endif
                    <span class="text-dark">{{ auth()->user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('role_users.edit', auth()->user()->id) }}">
                        <i class="align-middle me-1" data-feather="user"></i> Profile
                    </a>
                    <a class="dropdown-item"onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Log out</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                </div>
            </li>
        </ul>
    </div>
</nav>
