<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeUseCase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'home_use_case_title',
        'home_use_case_problem_title',
        'home_use_case_problem_text',
        'home_use_case_solution_title',
        'home_use_case_solution_text',
        'home_use_case_outcome_title',
        'home_use_case_outcome_text',
    ];
}
