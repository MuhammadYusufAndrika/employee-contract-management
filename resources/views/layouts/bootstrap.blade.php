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
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        #sidebar .sidebar-header h3 {
            color: #fff;
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
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
            <div>
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
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
