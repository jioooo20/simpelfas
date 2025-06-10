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
     * Get the status perbaikan for this perbaikan.
     */
    public function statusPerbaikan()
    {
        return $this->hasMany(StatusPerbaikanModel::class, 'perbaikan_id', 'perbaikan_id');
    }

    /**
     * Get the latest status perbaikan for this perbaikan.
     */
    public function latestStatusPerbaikan()
    {
        return $this->hasOne(StatusPerbaikanModel::class, 'perbaikan_id', 'perbaikan_id')
                    ->latest();
    }

    /**
     * Get all assigned technicians for this perbaikan.
     */
    public function perbaikanPetugas()
    {
        return $this->hasMany(PerbaikanPetugasModel::class, 'perbaikan_id', 'perbaikan_id');
    }

    /**
     * Get the user who is assigned to the perbaikan through pelaporan.
     */
    public function user()
    {
        return $this->hasOneThrough(
            UserModel::class,
            PelaporanModel::class,
            'pelaporan_id', // Foreign key on PelaporanModel
            'user_id', // Foreign key on UserModel
            'pelaporan_id', // Local key on PerbaikanModel
            'user_id' // Local key on PelaporanModel
        );
    }
}
