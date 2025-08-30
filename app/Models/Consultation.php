<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'booking_id',
        'consultation_slot_id',
        'consultation_notes',
    ];


    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function consultation_slot()
    {
        return $this->hasOne(ConsultationSlot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
