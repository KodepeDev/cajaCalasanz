<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'cuote_type_id',
    ];

    public function cuoteType()
    {
        return $this->belongsTo(CuoteType::class);
    }


    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
