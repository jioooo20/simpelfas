<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GdssResultModel extends Model
{
    use HasFactory;

    protected $table = 't_gdss_result';
    protected $primaryKey = 'gdss__id';

    protected $fillable = [
        'gdss_kode',
        'pelaporan_id',
        'nilai_skor',
        'rank',
    ];

    public function pelaporan()
    {
        return $this->belongsTo(PelaporanModel::class, 'pelaporan_id', 'pelaporan_id');
    }
}
