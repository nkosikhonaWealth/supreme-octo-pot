<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeSteps extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'home_steps_1_text',
        'home_steps_1_image',
        'home_steps_2_title',
        'home_steps_2_text',
        'home_steps_2_image',
        'home_steps_3_title',
        'home_steps_3_text',
        'home_steps_3_image',
        'home_steps_cta_label',
    ];
}
