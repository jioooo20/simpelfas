<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerbaikanModel extends Model
{
    use HasFactory;
    protected $table = 't_perbaikan';
    protected $primaryKey = 'perbaikan_id';
    public $incrementing = true;
    protected $fillable = [
        'pelaporan_id',
        'user_id',
        'perbaikan_kode',
        'perbaikan_deskripsi',
        'created_at',
        'updated_at'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function pelaporan()
    {
        return $this->hasMany(PelaporanModel::class, 'pelaporan_id', 'pelaporan_id');
    }
}
