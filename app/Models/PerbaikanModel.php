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
        'perbaikan_kode',
        'perbaikan_deskripsi',
        'created_at',
        'updated_at'
    ];
    
    /**
     * Get the pelaporan that owns the perbaikan.
     */
    public function pelaporan()
    {
        return $this->belongsTo(PelaporanModel::class, 'pelaporan_id', 'pelaporan_id');
    }
    
    /**
     * Get the user who is assigned to the perbaikan through pelaporan.
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            PelaporanModel::class,
            'pelaporan_id', // Foreign key on PelaporanModel
            'id', // Foreign key on User
            'pelaporan_id', // Local key on PerbaikanModel
            'user_id' // Local key on PelaporanModel
        );
    }
}
