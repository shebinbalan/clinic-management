<!-- resources/views/appointments/show.blade.php -->
@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Appointment Details</h4>
            </div>
            <div class="card-body">
                <!-- Patient & Doctor Info -->
                <div class="row mb-8">
                    <!-- Patient Info -->
                    <div class="col-md-6">
                        <h5>Patient Information</h5>
                        <p><strong>Name:</strong> {{ $appointment->patient->name }}</p>
                        <p><strong>Email:</strong> {{ $appointment->patient->email }}</p>
                        <p><strong>Phone:</strong> {{ $appointment->patient->phone ?: 'N/A' }}</p>
                    </div>

                    <!-- Doctor Info -->
                    <div class="col-md-6">
                        <h5>Doctor Information</h5>
                        <p><strong>Name:</strong> Dr. {{ $appointment->doctor->user->name }}</p>
                        <p><strong>Specialization:</strong> {{ $appointment->doctor->specialization }}</p>
                        <p><strong>Email:</strong> {{ $appointment->doctor->user->email }}</p>
                    </div>
                </div>

                <hr>

                <!-- Appointment Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Appointment Details</h5>
                        <p><strong>Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                        <p><strong>Time:</strong> {{ $appointment->appointment_time->format('H:i') }}</p>
                        <p><strong>Status:</strong>
                            <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h5>Notes</h5>
                        <p>{{ $appointment->notes ?: 'No notes provided' }}</p>
                    </div>
                </div>

                <hr>

                <!-- Buttons -->
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                        Back to Appointments
                    </a>

                    @if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            Update Status
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('appointments.update-status', $appointment) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="dropdown-item">Mark as Pending</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{route('appointments.update-status', $appointment) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="dropdown-item">Mark as Confirmed</button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('appointments.update-status', $appointment) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="dropdown-item">Mark as Cancelled</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
