<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusPelaporanModel extends Model
{
    use HasFactory;

    protected $table = 't_status_pelaporan';
    protected $primaryKey = 'status_pelaporan_id';

    protected $fillable = [
        'pelaporan_id',
        'status_pelaporan',
    ];

    /**
     * Relasi ke model Pelaporan
     */
    public function pelaporan(): BelongsTo
    {
        return $this->belongsTo(PelaporanModel::class, 'pelaporan_id', 'pelaporan_id');
    }
}
