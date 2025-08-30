<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultationSlot extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'date',
    ];
    public function booking()
    {
        return $this->hasMany(Booking::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
