<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    /**
     * Get the project that owns the media file.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
