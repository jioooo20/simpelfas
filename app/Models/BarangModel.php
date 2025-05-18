<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'm_barang';
    protected $primaryKey = 'barang_id';
    protected $fillable = ['barang_kode', 'barang_nama', 'deskripsi'];

    public function fasilitas()
    {
        return $this->hasMany(FasilitasModel::class, 'barang_id', 'barang_id');
    }
}

