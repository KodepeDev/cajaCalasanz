<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
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
        'is_ative',
        'is_tutor',
        'student_tutor_id',
    ];

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucfirst(strtolower($value));
    }
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucfirst(strtolower($value));
    }

    // public function getEtapaAttribute($value)
    // {
    //     if ($value == '1'){
    //         return '1ra Etapa';
    //     }else{
    //         return '2da Etapa';
    //     }
    // }
}
