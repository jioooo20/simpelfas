<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackModel extends Model
{
    use HasFactory;

    protected $table = 'm_feedback';
    protected $primaryKey = 'feedback_id';
    public $timestamps = true;


    protected $fillable = [
        'pelaporan_id',
        'feedback_text',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pelaporan(): BelongsTo
    {
        return $this->belongsTo(PelaporanModel::class, 'pelaporan_id', 'pelaporan_id');
    }
}
