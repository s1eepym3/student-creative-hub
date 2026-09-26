<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'nama_kegiatan',
        'penyelenggara',
        'tahun',
        'file_sertifikat',
    ];

    /**
     * Get the mahasiswa that owns the certificate.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
