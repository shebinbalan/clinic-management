@extends('layouts.app')

@section('title', 'Book Appointment')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Book New Appointment</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('appointments.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="doctor_id" class="form-label">Select Doctor</label>
                        <select class="form-control @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id" required>
                            <option value="">Choose a doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->user->name }} - {{ $doctor->specialization }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="appointment_date" class="form-label">Appointment Date</label>
                        <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                               id="appointment_date" name="appointment_date"
                               value="{{ old('appointment_date') }}" min="{{ date('Y-m-d') }}" required>
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">Appointment Time</label>
                        <select class="form-control @error('appointment_time') is-invalid @enderror" 
                                id="appointment_time" name="appointment_time" required>
                            <option value="">Select time slot</option>
                        </select>
                        @error('appointment_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="loading-slots" style="display: none;">
                            <small class="text-muted">Loading available slots...</small>
                        </div>
                        <div id="debug-info" style="display: none; margin-top: 10px;">
                            <small class="text-info">Debug info will appear here...</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="3" placeholder="Any special requirements or notes...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary" id="submit-btn">Book Appointment</button>
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let loadingSlots = false;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#doctor_id, #appointment_date').on('change', function() {
        let doctorId = $('#doctor_id').val();
        let date = $('#appointment_date').val();

        if (doctorId && date && !loadingSlots) {
            loadingSlots = true;
            $('#loading-slots').show();
            $('#appointment_time').prop('disabled', true);
            $('#debug-info').show().html('<small class="text-info">Loading slots...</small>');

            let url = '{{ url("/api/available-slots") }}';

            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    doctor_id: doctorId,
                    date: date
                },
                success: function(response) {
                    let select = $('#appointment_time');
                    select.empty().append('<option value="">Select time slot</option>');

                    if (Array.isArray(response) && response.length > 0) {
                        $.each(response, function(index, time) {
                            if (typeof time === 'string') {
                                select.append(`<option value="${time}">${formatTime(time)}</option>`);
                            } else {
                                console.warn('Invalid time slot format:', time);
                            }
                        });
                        $('#debug-info').html('<small class="text-success">Loaded ' + response.length + ' slots.</small>');
                    } else {
                        select.append('<option value="">No available slots</option>');
                        $('#debug-info').html('<small class="text-warning">No available slots for this date.</small>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading slots:', error);
                    $('#appointment_time').empty().append('<option value="">Error loading slots</option>');
                    $('#debug-info').html('<small class="text-danger">Error: ' + error + '</small>');
                },
                complete: function() {
                    loadingSlots = false;
                    $('#loading-slots').hide();
                    $('#appointment_time').prop('disabled', false);
                }
            });
        } else {
            $('#appointment_time').empty().append('<option value="">Select time slot</option>');
            $('#debug-info').hide();
        }
    });

    function formatTime(time) {
        if (typeof time !== 'string') return '';
        let [h, m] = time.split(':');
        let hours = parseInt(h);
        let period = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        return `${hours}:${m} ${period}`;
    }

    $('#submit-btn').on('click', function(e) {
        if (loadingSlots) {
            e.preventDefault();
            alert('Please wait while slots are loading...');
        }
    });
});
</script>


<!-- Add this to your layout if not already present -->
@if(!isset($csrfTokenSet))
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endif
@endsection