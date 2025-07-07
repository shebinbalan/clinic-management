
@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Appointments</h2>
            @if(auth()->user()->isPatient())
                <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Book New Appointment
                </a>
            @endif
        </div>
       
        <!-- Filter Form -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('appointments.index') }}">
                    <div class="row">
                        @if(auth()->user()->isAdmin())
                        <div class="col-md-3">
                            <select name="doctor_id" class="form-control">
                                <option value="">All Doctors</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                       
                        <div class="col-md-3">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                       
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary">Filter</button>
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
       
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                @if(!auth()->user()->isPatient())
                                    <th>Patient</th>
                                @endif
                                @if(!auth()->user()->isDoctor())
                                    <th>Doctor</th>
                                @endif
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                <td>{{ $appointment->appointment_time->format('H:i') }}</td>
                                @if(!auth()->user()->isPatient())
                                    <td>{{ $appointment->patient->name }}</td>
                                @endif
                                @if(!auth()->user()->isDoctor())
                                    <td>Dr. {{ $appointment->doctor->user->name }}</td>
                                @endif
                                <td>
                                    <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                   
                                    @if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                Status
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('appointments.update-status', $appointment) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="dropdown-item">Mark as Pending</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('appointments.update-status', $appointment) }}" method="POST">
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
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
               
                {{ $appointments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection


