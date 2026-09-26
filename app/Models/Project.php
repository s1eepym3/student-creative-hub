<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mahasiswa_id',
        'category_id',
        'judul',
        'slug',
        'deskripsi',
        'thumbnail',
        'project_url',
        'github_url',
        'visibility',
        'status',
        'rejection_reason',
        'admin_delete_reason',
    ];

    /**
     * Get the mahasiswa that owns the project.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Get the category that the project belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the media files for the project.
     */
    public function mediaFiles(): HasMany
    {
        return $this->hasMany(MediaFile::class);
    }

    /**
     * Get the technologies used in the project.
     */
    public function technologies(): HasMany
    {
        return $this->hasMany(ProjectTechnology::class);
    }

    /**
     * Get the likes for the project.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the showcase record associated with the project.
     */
    public function showcase(): HasOne
    {
        return $this->hasOne(Showcase::class);
    }
    /**
     * Get the revisions for the project.
     */
    public function revisions(): HasMany
    {
        return $this->hasMany(ProjectRevision::class);
    }

    /**
     * Get the views for the project.
     */
    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    /**
     * Scope a query to only include public projects.
     */
    public function scopePublic($query)
    {
        return $query->where('status', 'approved')->where('visibility', 'public');
    }

    /**
     * Scope a query to only include approved projects.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
