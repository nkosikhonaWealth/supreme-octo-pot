<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeHeader extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'home_header_title',
        'home_header_subtitle',
        'home_header_cta_label',
        'home_header_promo_text',
        'home_header_promo_image_1',
        'home_header_promo_image_2',
        'home_header_promo_image_3',
        'home_header_promo_image_4',
        'home_header_promo_cta',
        'home_header_image_1',
        'home_header_image_2',
        'home_header_image_3',
    ];
}
