<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
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
        'photo',
        'is_active',
        'description',
        'student_tutor_id',
        'teacher_id',
    ];

    public function summaries(){
        return $this->hasMany(Summary::class);
    }
    public function details(){
        return $this->hasMany(Detail::class);
    }
    public function cuotes(){
        return $this->hasMany(Cuote::class);
    }

    public function tutor()
    {
        return $this->belongsTo(StudentTutor::class, 'student_tutor_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // para relaciones de tablas base de ciclo escolar
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function currentEnrollment()
    {
        return $this->hasOne(Enrollment::class)->latestOfMany(); // Última matrícula del estudiante
    }

    public function grade()
    {
        return $this->hasOneThrough(Grade::class, Enrollment::class, 'student_id', 'id', 'id', 'grade_id');
    }

    public function section()
    {
        return $this->hasOneThrough(Section::class, Enrollment::class, 'student_id', 'id', 'id', 'section_id');
    }

    public function getGradeAndSectionAttribute()
    {
        $grade = $this->grade ? $this->grade->name : 'Sin grado';
        $section = $this->section ? $this->section->name : 'Sin sección';

        return "{$grade} - {$section}";
    }


    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtoupper($value);
    }
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtoupper($value);
    }

    public function getFotoAttribute()
    {
        if ($this->photo != null)
        {
            return (file_exists('storage/students/' .$this->photo) ? $this->photo : '../profile-default.png');
        }
        else
            return '../profile-default.png';
    }
}
