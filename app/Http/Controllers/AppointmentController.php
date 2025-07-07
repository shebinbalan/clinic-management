<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;

class AppointmentController extends Controller
{
   public function index(Request $request)
{
    $query = Appointment::with(['patient', 'doctor.user']);

    
    if (auth()->user()->role_type === 'patient') {
        $query->where('patient_id', auth()->user()->id);
    }

    
    if (auth()->user()->role_type === 'doctor') {
        $doctor = auth()->user()->doctor;
        if ($doctor) {
            $query->where('doctor_id', $doctor->id);
        }
    }

    // Admin aanenkil filters apply cheyyam
    if ($request->has('doctor_id') && $request->doctor_id) {
        $query->where('doctor_id', $request->doctor_id);
    }

    if ($request->has('patient_id') && $request->patient_id) {
        $query->where('patient_id', $request->patient_id);
    }

    if ($request->has('date') && $request->date) {
        $query->whereDate('appointment_date', $request->date);
    }

    if ($request->has('status') && $request->status) {
        $query->where('status', $request->status);
    }

    $appointments = $query->orderBy('appointment_date', 'desc')
                         ->orderBy('appointment_time', 'desc')
                         ->paginate(10);

    $doctors = Doctor::with('user')->get();

    return view('appointments.index', compact('appointments', 'doctors'));
}

   public function create()
    {
        $doctors = Doctor::with('user')->get();
        return view('appointments.create', compact('doctors'));
    }


public function getAvailableSlots(Request $request)
{
    try {
        $doctor = Doctor::find($request->doctor_id);
        $date = $request->date;

        if (!$doctor || !$date) {
            return response()->json([]);
        }

        $dayOfWeek = \Carbon\Carbon::parse($date)->format('l');
        $availability = $doctor->availability_slots;
        $ranges = $availability[$dayOfWeek] ?? [];

        $availableSlots = [];

        // ✅ You use fixed time slots, not ranges
        foreach ($ranges as $time) {
            if (!is_array($time)) {
                $availableSlots[] = $time;
            }
        }

        $bookedSlots = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $date)
            ->pluck('appointment_time')
            //  ->whereIn('status', ['pending', 'confirmed']) // ✅ Only these block the slot
            ->map(fn($time) => \Carbon\Carbon::parse($time)->format('H:i'))
            ->toArray();

        $freeSlots = array_diff($availableSlots, $bookedSlots);

        return response()->json(array_values($freeSlots));
    } catch (\Throwable $e) {
        \Log::error($e);
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}


    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string|max:500'
        ]);

        // Check if doctor already has appointment at this time
        $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingAppointment) {
            return back()->withErrors(['appointment_time' => 'This time slot is no longer available.']);
        }

        // Prevent same patient from double-booking
        $patientConflict = Appointment::where('patient_id', auth()->id())
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($patientConflict) {
            return back()->withErrors(['appointment_time' => 'You already have an appointment at this time.']);
        }

        Appointment::create([
            'patient_id' => auth()->id(),
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully!');
    }

  
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.user']);
        return view('appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Appointment status updated successfully!');
    }

 
}