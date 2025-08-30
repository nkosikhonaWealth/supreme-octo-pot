<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'consultation_slot_id',
        'booking_status',
        'payment_status',
        'booking_fee',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultation_slot()
    {
        return $this->belongsTo(ConsultationSlot::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }
}
