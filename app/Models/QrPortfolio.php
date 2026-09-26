<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrPortfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'qr_path',
    ];

    /**
     * Get the mahasiswa that owns the QR portfolio.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
