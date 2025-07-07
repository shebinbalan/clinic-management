@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user-md"></i> Dr. {{ $doctor->user->name }}</h2>
            <div>
                <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('doctors.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> Doctor Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Name:</th>
                        <td>Dr. {{ $doctor->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $doctor->user->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td>{{ $doctor->user->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Specialization:</th>
                        <td>{{ $doctor->specialization }}</td>
                    </tr>
                    <tr>
                        <th>Consultation Fee:</th>
                        <td>${{ number_format($doctor->consultation_fee, 2) }}</td>
                    </tr>
                    <tr>
    <th>Availability Slots:</th>
    <td>
        @if(is_array($doctor->availability_slots) && count($doctor->availability_slots))
            <ul class="mb-0">
                @foreach($doctor->availability_slots as $day => $range)
                    <li>
                        <strong>{{ $day }}:</strong>
                        @if(is_array($range) && count($range) == 2)
                            {{ $range[0] }} - {{ $range[1] }}
                        @elseif(is_array($range) && count($range))
                            {{-- In case of multiple slots per day --}}
                            @foreach($range as $slot)
                                @if(is_array($slot) && count($slot) == 2)
                                    {{ $slot[0] }} - {{ $slot[1] }}<br>
                                @endif
                            @endforeach
                        @else
                            <span class="text-muted">Not available</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <span class="text-muted">No availability set</span>
        @endif
    </td>
</tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
