@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-user-md"></i> Doctors</h2>
            <a href="{{ route('doctors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Doctor
            </a>
        </div>
    </div>
</div>

<div class="row">
    @foreach($doctors as $doctor)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Dr. {{ $doctor->user->name }}</h5>
                    <p class="card-text">
                        <strong>Specialization:</strong> {{ $doctor->specialization }}<br>
                        <strong>Email:</strong> {{ $doctor->user->email }}<br>
                        <strong>Phone:</strong> {{ $doctor->user->phone }}<br>
                        <strong>Consultation Fee:</strong> ${{ $doctor->consultation_fee }}
                    </p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('doctors.destroy', $doctor) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection