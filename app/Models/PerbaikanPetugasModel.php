<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerbaikanPetugasModel extends Model
{
    use HasFactory;
    
    protected $table = 't_perbaikan_petugas';
    protected $primaryKey = 'perbaikan_petugas_id';
    public $incrementing = true;
    
    protected $fillable = [
        'perbaikan_id',
        'user_id',
        'created_at',
        'updated_at',
    ];
    
    /**
     * Get the perbaikan associated with this assignment.
     */
    public function perbaikan()
    {
        return $this->belongsTo(PerbaikanModel::class, 'perbaikan_id', 'perbaikan_id');
    }
    
    /**
     * Get the user (petugas) assigned to this repair task.
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
    
    /**
     * Scope a query to filter by specific user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    /**
     * Scope a query to filter by specific perbaikan.
     */
    public function scopeByPerbaikan($query, $perbaikanId)
    {
        return $query->where('perbaikan_id', $perbaikanId);
    }
}
