<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeBenefits extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'home_benefits_title',
        'home_benefits_subtitle',
        'home_benefits_intro',
        'home_benefits_list',
        'home_benefits_outro',
        'home_benefits_cta_label',
        'home_benefits_cta_image',
    ];
}
