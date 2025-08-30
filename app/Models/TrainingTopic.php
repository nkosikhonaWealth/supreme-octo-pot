<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingTopic extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    public function subtopics()
    {
        return $this->hasMany(TrainingTopic::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(TrainingTopic::class, 'parent_id');
    }
}
