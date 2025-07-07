@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2><i class="fas fa-user-md"></i> Doctor Dashboard</h2>
        <p class="text-muted">Welcome back, Dr. {{ auth()->user()->name }}!</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-calendar-alt"></i> Upcoming Appointments</h5>
            </div>
            <div class="card-body">
                @if($upcoming_appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcoming_appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                        <td>{{ $appointment->patient->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No upcoming appointments.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection