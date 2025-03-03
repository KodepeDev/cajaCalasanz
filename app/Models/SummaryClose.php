<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummaryClose extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'from_date',
        'to_date',
        'petty_cash',
        'previous_balance',
        'previous_income_balance',
        'previous_expense_balance',
        'current_balance',
        'current_income_balance',
        'current_expense_balance',
        'account_balance',// para balance por cuentas
        'current_income_nulled',
        'current_expense_nulled',
        'current_nulled',
        'generated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'from_date' => 'datetime',
        'to_date' => 'datetime',
        'account_balance' => 'array',
    ];
}
