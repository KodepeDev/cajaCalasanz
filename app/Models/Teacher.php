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

    public function grade()
    {
        return $this->hasOneThrough(Grade::class, TeacherAssignment::class, 'teacher_id', 'id', 'id', 'grade_id');
    }

    public function section()
    {
        return $this->hasOneThrough(Section::class, TeacherAssignment::class, 'teacher_id', 'id', 'id', 'section_id');
    }
}
