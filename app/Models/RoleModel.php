<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;

    protected $table = 'm_role';
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'role_kode',
        'role_nama',
        'role_deskripsi',
    ];

    public function user()
    {
        return $this->hasMany(UserModel::class, 'role_id', 'role_id');
    }
}
