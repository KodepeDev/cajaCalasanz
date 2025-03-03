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
        'grade',
        'is_active',
        'description',
        'student_tutor_id',
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
