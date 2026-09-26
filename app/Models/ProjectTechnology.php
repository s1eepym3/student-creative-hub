<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTechnology extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'technology_name',
    ];

    /**
     * Get the project that owns the technology.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
