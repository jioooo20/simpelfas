<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RuangModel;

class LantaiModel extends Model
{
    use HasFactory;
    protected $table = 'm_lantai';
    protected $primaryKey = 'lantai_id';
    protected $fillable = [
        'lantai_id',
        'gedung_id',
        'lantai_kode',
        'lantai_nama',
        'lantai_deskripsi',
        'created_at',
        'updated_at'
    ];

    public function gedung()
    {
        return $this->belongsTo(GedungModel::class, 'gedung_id', 'gedung_id');
    }
    public function ruang()
    {
        return $this->hasMany(RuangModel::class, 'lantai_id', 'lantai_id');
    }
}
