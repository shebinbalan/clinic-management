@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Admin Dashboard</h2>
                <p class="text-muted mb-0">Welcome back, <strong>{{ auth()->user()->name }}</strong> 👋</p>
            </div>
            <div>
                <!-- <span class="badge bg-success fs-6">Role: Admin</span> -->
            </div>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-gradient bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Patients</h6>
                        <h3 class="fw-bold">{{ $total_patients }}</h3>
                    </div>
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-gradient bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Doctors</h6>
                        <h3 class="fw-bold">{{ $total_doctors }}</h3>
                    </div>
                    <i class="fas fa-user-md fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-gradient bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Appointments</h6>
                        <h3 class="fw-bold">{{ $total_appointments }}</h3>
                    </div>
                    <i class="fas fa-calendar-alt fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-gradient bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Pending Appointments</h6>
                        <h3 class="fw-bold">{{ $pending_appointments }}</h3>
                    </div>
                    <i class="fas fa-clock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row mt-5">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-bolt text-primary me-1"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('doctors.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-1"></i> Add Doctor
                    </a>
                    <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-1"></i> Manage Doctors
                    </a>
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-info">
                        <i class="fas fa-calendar me-1"></i> View Appointments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
