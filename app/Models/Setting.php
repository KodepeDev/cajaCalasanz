<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_ruc',
        'email',
        'phone',
        'logo',
        'before_date_add',
        'number_of_days_add',
        'before_date_out',
        'number_of_days_out',
        'report_type',
        'receipt_type',
        'default_currency',
    ];

    public function getFotoAttribute()
    {
        if ($this->logo != null)
        {
            // dd($this->logo);
            return (file_exists('storage/system/' .$this->logo) ? asset('storage/system/'. $this->logo) : asset('imagenes/profile-default.png'));
        }
        else
            return asset('imagenes/profile-default.png');
    }
}
