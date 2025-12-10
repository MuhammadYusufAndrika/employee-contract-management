<button class="btn btn-primary sidebar-toggle">
    <i class="bi bi-list"></i>
</button>

<nav id="sidebar">
    <div class="sidebar-header">
        <h3>
            <i class="bi bi-file-earmark-text"></i>
            <span>Contract Management</span>
        </h3>
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('contracts.index') }}" class="nav-link {{ request()->routeIs('contracts.*') && !request()->routeIs('contracts.expiring') ? 'active' : '' }}">
                <i class="bi bi-file-text"></i>
                <span>Contracts</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('contract-histories.index') }}" class="nav-link {{ request()->routeIs('contract-histories.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span>Contract History</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('contracts.expiring') }}" class="nav-link {{ request()->routeIs('contracts.expiring') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i>
                <span>Expiring Soon</span>
                @php
                    $expiringCount = \App\Models\Contract::expiringWithinDays(30)->count();
                @endphp
                @if($expiringCount > 0)
                    <span class="badge bg-danger nav-badge">{{ $expiringCount }}</span>
                @endif
            </a>
        </li>
    </ul>
    
    <div class="sidebar-footer">
        @auth
            <div class="user-info">
                <i class="bi bi-person-circle"></i>
                <div>
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-email">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-light btn-sm mt-2 w-100">
                <i class="bi bi-person"></i> <span>Profile</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm logout-btn">
                    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100 mb-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm w-100">Register</a>
        @endauth
    </div>
</nav>
