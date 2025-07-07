<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Clinic Management') }}</title>

    <!-- Prevent caching after logout -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            position: sticky;
            top: 0;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
        }
        .sidebar .list-group-item:hover {
            background-color: #f1f1f1;
        }
        .list-group-item.active {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <!-- Toggle button for mobile -->
        <button class="btn btn-outline-light d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenuMobile">
            <i class="fas fa-bars"></i>
        </button>

        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="fas fa-hospital-user me-1"></i> Clinic Management
        </a>

        @if(auth()->check())
            <div class="ms-auto me-3 text-white d-flex align-items-center">
                <i class="fas fa-user me-2"></i>
                <span>{{ auth()->user()->name }}</span>
            </div>
        @endif
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar for large screens -->
        <div class="col-lg-2 d-none d-lg-block sidebar p-0">
            <div class="list-group list-group-flush">
                
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard
                </a>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('patients.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i class="fas fa-user-injured me-2 text-success"></i> Patients
                    </a>
                    <a href="{{ route('doctors.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                        <i class="fas fa-user-md me-2 text-secondary"></i> Doctors
                    </a>
                @endif

                <a href="{{ route('appointments.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt me-2 text-info"></i> Appointments
                </a>

                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </div>

        <!-- Offcanvas Sidebar for small screens -->
        <div class="offcanvas offcanvas-start d-lg-none" id="sidebarMenuMobile">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-0">
                <div class="list-group list-group-flush">
                    
                    <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i> Dashboard
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('patients.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                            <i class="fas fa-user-injured me-2 text-success"></i> Patients
                        </a>
                        <a href="{{ route('doctors.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                            <i class="fas fa-user-md me-2 text-secondary"></i> Doctors
                        </a>
                    @endif

                    <a href="{{ route('appointments.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt me-2 text-info"></i> Appointments
                    </a>

                    <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main class="col-lg-10 py-4 px-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
