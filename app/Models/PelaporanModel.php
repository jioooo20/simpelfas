<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class PelaporanModel extends Model
{
    use HasFactory;

    protected $table = 'm_pelaporan';
    protected $primaryKey = 'pelaporan_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'fasilitas_id',
        'pelaporan_kode',
        'pelaporan_deskripsi',
        'pelaporan_gambar',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function fasilitas(): BelongsTo
    {
        return $this->belongsTo(FasilitasModel::class, 'fasilitas_id', 'fasilitas_id');
    }

    public function statusPelaporan(): HasMany
    {
        return $this->hasMany(StatusPelaporanModel::class, 'pelaporan_id', 'pelaporan_id');
    }

    public function skorAlternatif()
    {
        return $this->hasMany(SkorAltModel::class, 'pelaporan_id', 'pelaporan_id');
    }
    public function perbaikan()
{
    return $this->hasOne(PerbaikanModel::class, 'pelaporan_id', 'pelaporan_id');
}

public function feedback()
{
    return $this->hasOne(FeedbackModel::class, 'pelaporan_id', 'pelaporan_id');
}
}
