<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTutor extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'email',
        'document_type',
        'document',
        'phone',
        'address',
        'type',
        'is_ative',
        'is_client',
        'description',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
