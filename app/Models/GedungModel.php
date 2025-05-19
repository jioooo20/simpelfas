<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GedungModel extends Model
{
    use HasFactory;
    protected $table = 'm_gedung';
    protected $primaryKey = 'gedung_id';
    protected $fillable = [
        'gedung_id',
        'gedung_kode',
        'gedung_nama',
        'gedung_keterangan',
        'created_at',
        'updated_at'
    ];

    public function lantai()
    {
        return $this->hasMany(LantaiModel::class, 'gedung_id', 'gedung_id');
    }
}
