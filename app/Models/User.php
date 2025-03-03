<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'document',
        'email',
        'status',
        'email_verified_at',
        'password',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function summaries()
    {
        return $this->hasMany(Summary::class);
    }

    public function getFotoAttribute()
    {
        if ($this->profile_image != null)
        {
            return (file_exists('storage/usuarios/' .$this->profile_image) ? $this->profile_image : '../profile-default.png');
        }
        else
            return '../profile-default.png';
    }

    public function bitacora()
    {
        return $this->hasMany(Bitacora::class);
    }

    public function adminlte_desc()
    {
        return $this->last_name;
    }

    public function adminlte_profile_url()
    {
        return 'admin/user_profile';
    }
    public function adminlte_image()
    {
        return '../profile-default.png';
    }

    public function misRecibos($fisrt_day, $last_day, $type, $account_id)
    {
        return $this->hasMany(Summary::class)
            ->when($fisrt_day && $last_day, function($query) use ($fisrt_day, $last_day) {
                $query->whereBetween('date', [$fisrt_day, $last_day]);
            })
            ->when($type, function($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($account_id, function($query) use ($account_id){
                $query->where('account_id', $account_id);
            })
            ->orderBy('date', 'asc');
    }
}
