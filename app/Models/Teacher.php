<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'email',
        'document_type',
        'document',
        'phone',
        'photo',
        'is_active',
    ];
}
