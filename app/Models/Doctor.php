<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'availability_slots',
        'consultation_fee',
    ];

    protected $casts = [
        'availability_slots' => 'array',
         'schedule' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }



   

    public function getAvailableSlotsForDate($date)
{
    $dayOfWeek = Carbon::parse($date)->format('l'); 

    $schedule = $this->schedule; 

    if (!isset($schedule[$dayOfWeek])) {
        return []; 
    }

    [$startTime, $endTime] = $schedule[$dayOfWeek];

    $start = Carbon::parse($startTime);
    $end = Carbon::parse($endTime);
    $slotDuration = 30; // minutes

    $allSlots = [];

    while ($start->lt($end)) {
        $slot = $start->format('H:i');
        $allSlots[] = $slot;
        $start->addMinutes($slotDuration);
    }

    
    $bookedSlots = $this->appointments()
        ->where('appointment_date', $date)
        ->pluck('appointment_time')
        ->map(function ($time) {
            return Carbon::parse($time)->format('H:i');
        })
        ->toArray();

 
    return array_values(array_diff($allSlots, $bookedSlots));
}
}
