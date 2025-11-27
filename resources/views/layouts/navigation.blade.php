<aside id="sidebar" class="sidebar offcanvas-md offcanvas-start bg-white border-end d-flex flex-column" tabindex="-1">
    <div class="offcanvas-header d-md-none border-bottom">
        <h5 class="offcanvas-title mb-0">{{ config('app.name', 'Laravel') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body d-flex flex-column p-0 flex-grow-1">
        <!-- Logo -->
        <div class="p-3 border-bottom">
            <a href="{{ route('dashboard') }}" class="text-decoration-none">
                <x-application-logo class="d-block" style="height: 2rem; width: auto;" />
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-grow-1 p-3">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>
                        {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>
        </nav>

        <!-- User Section -->
        <div class="p-3 border-top">
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none text-dark w-100 text-start p-0 d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ Auth::user()->name }}</div>
                        <small class="text-muted d-block">{{ Auth::user()->email }}</small>
                    </div>
                    <i class="bi bi-chevron-down ms-2"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end w-100" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2"></i>
                            {{ __('Profile') }}
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Toggle Button -->
<button class="btn btn-primary d-md-none position-fixed top-0 start-0 m-3 rounded-circle" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" style="z-index: 1040; width: 40px; height: 40px; padding: 0;">
    <i class="bi bi-list"></i>
</button>
