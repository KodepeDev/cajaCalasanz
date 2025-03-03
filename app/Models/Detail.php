<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_code',
        'status',
        'summary_type',
        'type',
        'description',
        'amount',
        'changed_amount',
        'date',
        'date_paid',
        'category_id',
        'student_id',
        'student_tutor_id',
        'summary_id',
        'currency_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'date' => 'datetime',
        'date_paid' => 'datetime',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function student(){
        return $this->belongsTo(Student::class);
    }
    public function tutor(){
        return $this->belongsTo(StudentTutor::class);
    }
    public function summary(){
        return $this->belongsTo(Summary::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }
    public function getCurrencyIdAttribute($value)
    {
        // Si el valor es null, retorna un valor predeterminado (ej. 1)
        return $value ?: 1;
    }

}
