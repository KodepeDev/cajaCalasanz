<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;


    public function exchangeRates()
    {
        return $this->hasMany(ExchangeRate::class, 'base_currency_id');
    }

    public function quoteExchangeRates()
    {
        return $this->hasMany(ExchangeRate::class, 'quote_currency_id');
    }
}
