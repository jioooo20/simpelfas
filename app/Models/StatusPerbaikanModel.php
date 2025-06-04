<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPerbaikanModel extends Model
{
    use HasFactory;
    
    protected $table = 't_status_perbaikan';
    protected $primaryKey = 'status_perbaikan_id';
    public $incrementing = true;
    
    protected $fillable = [
        'perbaikan_id',
        'perbaikan_gambar',
        'perbaikan_status',
        'created_at',
        'updated_at'
    ];
    
    protected $casts = [
        'perbaikan_status' => 'string',
    ];
    
    /**
     * Get the perbaikan that owns this status.
     */
    public function perbaikan()
    {
        return $this->belongsTo(PerbaikanModel::class, 'perbaikan_id', 'perbaikan_id');
    }
    
    /**
     * Scope a query to only include status with specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('perbaikan_status', $status);
    }
    
    /**
     * Check if the status is "Menunggu"
     */
    public function isMenunggu()
    {
        return $this->perbaikan_status === 'Menunggu';
    }
    
    /**
     * Check if the status is "Diproses"
     */
    public function isDiproses()
    {
        return $this->perbaikan_status === 'Diproses';
    }
    
    /**
     * Check if the status is "Selesai"
     */
    public function isSelesai()
    {
        return $this->perbaikan_status === 'Selesai';
    }
}
