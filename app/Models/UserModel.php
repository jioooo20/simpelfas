<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'role_id',
        'identitas',
        'nama',
        'password',
        'email',
        'profile_image',
    ];
    protected $hidden = [
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id', 'role_id');
    }
}
