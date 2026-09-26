<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectRevisionMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_revision_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    /**
     * Get the revision that owns this media file.
     */
    public function projectRevision(): BelongsTo
    {
        return $this->belongsTo(ProjectRevision::class);
    }
}
