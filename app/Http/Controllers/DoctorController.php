<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->get();
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string|max:255',
            'consultation_fee' => 'required|numeric|min:0',
            'availability_slots' => 'required|array',
        ]);

       

        $availability = $request->availability_slots;

            if (!is_array($availability)) {
                return back()->withErrors(['availability_slots' => 'Invalid format.']);
            }

       
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role_type' => 'doctor',
        ]);

        $user->assignRole('doctor');

        Doctor::create([
            'user_id' => $user->id,
            'specialization' => $request->specialization,
             'availability_slots' => $availability, 
            'consultation_fee' => $request->consultation_fee,
        ]);

        return redirect()->route('doctors.index')->with('success', 'Doctor added successfully!');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('user', 'appointments.patient');
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $doctor->load('user');
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->user_id,
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string|max:255',
            'consultation_fee' => 'required|numeric|min:0',
            'availability_slots' => 'required|array',
        ]);

         $availability = $request->availability_slots;

            if (!is_array($availability)) {
                return back()->withErrors(['availability_slots' => 'Invalid format.']);
            }

        $doctor->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $doctor->update([
            'specialization' => $request->specialization,
            'availability_slots' => $availability,
            'consultation_fee' => $request->consultation_fee,
        ]);

        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully!');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->user->delete();
        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully!');
    }
}
