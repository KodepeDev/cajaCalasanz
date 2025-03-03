<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuoteType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function cuotes()
    {
        return $this->hasMany(Cuote::class);
    }
}
