<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FasilitasModel extends Model
{
    use HasFactory;

    protected $table = 't_fasilitas';
    protected $primaryKey = 'fasilitas_id';
    protected $fillable = [
        'ruang_id',
        'barang_id',
        'fasilitas_kode',
        'fasilitas_status'
    ];

    public function ruang(): BelongsTo
    {
        return $this->belongsTo(RuangModel::class, 'ruang_id', 'ruang_id');
    }

    public function barang() : BelongsTo
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }
}
