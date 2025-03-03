<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NulledDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'summary_type',
        'type',
        'description',
        'amount',
        'date',
        'date_paid',
        'category',
        'student',
        'summary_id',
        'currency_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'date' => 'datetime',
        'date_paid' => 'datetime',
    ];

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
