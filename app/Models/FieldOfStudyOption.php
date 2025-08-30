<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldOfStudyOption extends Model
{
    protected $fillable = [
        'name',
    ];

    public static function getOptions(): array
    {
        return static::orderBy('name')->pluck('name', 'name')->toArray();
    }

    public static function addOption(string $name): void
    {
        static::firstOrCreate(['name' => trim($name)]);
    }
}
