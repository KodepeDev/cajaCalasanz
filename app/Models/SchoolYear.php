<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'is_active',
    ];

    public static function current()
    {
        return self::where('is_active', true)->first();
    }
}
