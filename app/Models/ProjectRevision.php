<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'category_id',
        'judul',
        'deskripsi',
        'thumbnail',
        'project_url',
        'github_url',
        'visibility',
        'status',
        'rejection_reason',
    ];

    /**
     * Get the project that this revision belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the category that the revised project belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the media files for the revision.
     */
    public function mediaFiles(): HasMany
    {
        return $this->hasMany(ProjectRevisionMedia::class);
    }

    /**
     * Get the technologies used in the revision.
     */
    public function technologies(): HasMany
    {
        return $this->hasMany(ProjectRevisionTechnology::class);
    }
}
