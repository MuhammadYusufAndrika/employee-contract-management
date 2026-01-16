<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Contract Management')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            overflow-x: hidden;
        }
        
        #sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1d29 0%, #2c3142 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
            width: 250px;
        }
        
        #sidebar.collapsed {
            width: 70px;
            overflow: hidden;
        }
        
        #sidebar.collapsed .sidebar-header h3 {
            font-size: 1.5rem;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        #sidebar.collapsed .sidebar-header h3 i {
            margin-right: 0;
        }
        
        #sidebar.collapsed .sidebar-header h3 span {
            display: none;
        }
        
        #sidebar.collapsed ul.nav li a span {
            display: none;
        }
        
        #sidebar.collapsed ul.nav li a {
            justify-content: center;
            padding: 12px 10px;
        }
        
        #sidebar.collapsed ul.nav li a i {
            margin-right: 0;
            font-size: 1.3rem;
        }
        
        #sidebar.collapsed .nav-badge {
            right: 10px;
            font-size: 0.65rem;
            padding: 2px 4px;
        }
        
        #sidebar.collapsed .sidebar-footer .user-info div,
        #sidebar.collapsed .sidebar-footer .btn span,
        #sidebar.collapsed .sidebar-footer .btn-sm {
            display: none;
        }
        
        #sidebar.collapsed .sidebar-footer .user-info {
            justify-content: center;
            padding: 10px;
        }
        
        #sidebar.collapsed .sidebar-footer .user-info i {
            margin-right: 0;
        }
        
        #sidebar.collapsed .sidebar-footer .btn {
            padding: 8px;
            text-align: center;
        }
        
        #sidebar.collapsed .logout-btn {
            display: block !important;
            padding: 8px;
        }
        
        #sidebar.collapsed .logout-btn span {
            display: none;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            overflow: hidden;
        }
        
        #sidebar .sidebar-header h3 {
            color: #fff;
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }
        
        #sidebar .sidebar-header h3 i {
            margin-right: 10px;
            flex-shrink: 0;
        }
        
        #sidebar ul.nav {
            padding: 20px 0;
        }
        
        #sidebar ul.nav li {
            margin-bottom: 5px;
        }
        
        #sidebar ul.nav li a {
            padding: 12px 25px;
            color: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            position: relative;
        }
        
        #sidebar ul.nav li a i {
            margin-right: 10px;
            font-size: 1.1rem;
            width: 25px;
        }
        
        #sidebar ul.nav li a:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: #0d6efd;
        }
        
        #sidebar ul.nav li a.active {
            background: rgba(13,110,253,0.2);
            color: #fff;
            border-left-color: #0d6efd;
        }
        
        /* Dropdown menu styles */
        #sidebar ul.nav li.dropdown .dropdown-toggle {
            cursor: pointer;
        }
        
        #sidebar ul.nav li.dropdown .dropdown-toggle i.bi-chevron-down {
            transition: transform 0.3s;
            font-size: 0.8rem;
        }
        
        #sidebar ul.nav li.dropdown .dropdown-toggle[aria-expanded="true"] i.bi-chevron-down {
            transform: rotate(180deg);
        }
        
        #sidebar ul.nav li ul.collapse {
            background: rgba(0,0,0,0.2);
            padding: 0;
        }
        
        #sidebar ul.nav li ul.collapse li a.submenu-link {
            padding: 10px 25px 10px 45px;
            font-size: 0.95rem;
            border-left: 3px solid transparent;
        }
        
        #sidebar ul.nav li ul.collapse li a.submenu-link:hover {
            background: rgba(255,255,255,0.15);
        }
        
        #sidebar ul.nav li ul.collapse li a.submenu-link.active {
            background: rgba(13,110,253,0.3);
            border-left-color: #0d6efd;
        }
        
        #sidebar.collapsed ul.nav li.dropdown .dropdown-toggle i.bi-chevron-down {
            display: none;
        }
        
        #sidebar.collapsed ul.nav li ul.collapse {
            display: none !important;
        }
        
        #sidebar .nav-badge {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        #content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background: #f8f9fa;
            transition: all 0.3s;
        }
        
        #content.expanded {
            margin-left: 70px;
        }
        
        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            margin: -20px -20px 20px -20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .sidebar-toggle-btn {
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px 10px;
            margin-right: 15px;
            transition: all 0.3s;
        }
        
        .sidebar-toggle-btn:hover {
            color: #0d6efd;
            transform: scale(1.1);
        }
        
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-footer .user-info {
            color: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            background: rgba(255,255,255,0.05);
        }
        
        .sidebar-footer .user-info i {
            font-size: 2rem;
            margin-right: 12px;
        }
        
        .sidebar-footer .user-name {
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .sidebar-footer .user-email {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .logout-btn {
            margin-top: 10px;
            width: 100%;
        }
        
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: block !important;
            }
        }
        
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 999;
        }
    </style>
</head>
<body>
    @include('layouts.bootstrap-nav')
    
    <div id="content">
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle-btn" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h4 class="mb-0">@yield('title', 'Dashboard')</h4>
            </div>
            <div>
                <span class="text-muted"><i class="bi bi-calendar3"></i> {{ now()->format('l, d F Y') }}</span>
            </div>
        </div>
        
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Whoops!</strong> There were some problems with your input.
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
            @yield('content')
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            
            // Load saved state
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                content.classList.add('expanded');
            }
            
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
                
                // Save state
                const collapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', collapsed);
            });
            
            // Mobile sidebar toggle
            const mobileSidebarToggle = document.querySelector('.sidebar-toggle');
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
