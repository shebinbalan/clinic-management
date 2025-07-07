<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $data = [
                'total_patients' => User::where('role_type', 'patient')->count(),
                'total_doctors' => Doctor::count(),
                'total_appointments' => Appointment::count(),
                'pending_appointments' => Appointment::where('status', 'pending')->count(),
            ];
            return view('dashboard.admin', $data);
        } elseif ($user->isDoctor()) {
            $doctor = $user->doctor;
            $data = [
                'upcoming_appointments' => $doctor->appointments()
                    ->where('appointment_date', '>=', now()->toDateString())
                    ->with('patient')
                    ->orderBy('appointment_date')
                    ->orderBy('appointment_time')
                    ->get(),
            ];
            return view('dashboard.doctor', $data);
        } else {
            $data = [
                'my_appointments' => $user->patientAppointments()
                    ->with('doctor.user')
                    ->orderBy('appointment_date', 'desc')
                    ->get(),
            ];
            return view('dashboard.patient', $data);
        }
    }
}