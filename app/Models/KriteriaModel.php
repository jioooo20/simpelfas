<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KriteriaModel extends Model
{
    use HasFactory;

    protected $table = 'm_kriteria';
    protected $primaryKey = 'kriteria_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kriteria_kode',
        'kriteria_nama',
        'kriteria_jenis',
        'w1_mhs',
        'w2_dsn',
        'w3_stf',
    ];

    public function skorAlternatif(): HasMany
    {
        return $this->hasMany(SkorAltModel::class, 'kriteria_id', 'kriteria_id');
    }
}
