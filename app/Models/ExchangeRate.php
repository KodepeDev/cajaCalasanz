<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'base_currency_id', 'quote_currency_id', 'rate'];

    public function baseCurrency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function quoteCurrency()
    {
        return $this->belongsTo(Currency::class);
    }
}
