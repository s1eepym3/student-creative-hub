<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectRevisionTechnology extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_revision_id',
        'technology_name',
    ];

    /**
     * Get the revision that owns this technology.
     */
    public function projectRevision(): BelongsTo
    {
        return $this->belongsTo(ProjectRevision::class);
    }
}
