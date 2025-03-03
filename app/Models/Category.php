<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
    ];

    public function details(){
        return $this->hasMany(Detail::class);
    }
    public function detalles($cat, $fisrt_day, $last_day, $stat){

        // dd($cat, $fisrt_day, $last_day, $stat);
        return $this->hasMany(Detail::class)
                ->when($cat, function($query) use ($cat) {
                    $query->where('category_id', $cat);
                })
                ->whereStatus($stat)
                ->when($fisrt_day, function($query) use ($fisrt_day, $last_day) {
                    $query->whereBetween('date', [$fisrt_day, $last_day]);
                })
                ->orderBy('date', 'asc');
    }

    public function detallePendientes($cat, $check, $fisrt_day, $last_day, $stat){

        // dd($cat, $fisrt_day, $last_day, $stat);

        return $this->hasMany(Detail::class)
                ->when($cat, function($query) use ($cat) {
                    $query->where('category_id', $cat);
                })
                ->whereStatus($stat)
                ->when($check, function($query) use ($fisrt_day, $last_day) {
                    $query->whereBetween('date', [$fisrt_day, $last_day]);
                })
                ->orderBy('date', 'asc');
    }
}
