<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkorAltModel extends Model
{
    use HasFactory;

    protected $table = 'm_skor_alt';
    protected $primaryKey = 'skor_alt_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'skor_alt_kode',
        'pelaporan_id',
        'kriteria_id',
        'nilai_skor',
    ];

    public function pelaporan(): BelongsTo
    {
        return $this->belongsTo(PelaporanModel::class, 'pelaporan_id', 'pelaporan_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(KriteriaModel::class, 'kriteria_id', 'kriteria_id');
    }
    // Di SkorAltModel.php
    public function getLabelAttribute()
    {
        $kriteria = $this->kriteria->kriteria_nama;
        
        return match ($kriteria) {
            'Skala Kerusakan' => match ($this->nilai_skor) {
                1 => 'Ringan',
                2 => 'Sedang',
                3 => 'Berat',
                default => 'Tidak Diketahui'
            },
            'Frekuensi Penggunaan' => match ($this->nilai_skor) {
                1 => 'Jarang',
                2 => 'Sedang',
                3 => 'Sering',
                default => 'Tidak Diketahui'
            },
            default => (string) $this->nilai_skor
        };
    }
}
