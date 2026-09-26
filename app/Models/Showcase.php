<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Showcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'featured_by',
        'featured_at',
    ];

    protected function casts(): array
    {
        return [
            'featured_at' => 'datetime',
        ];
    }

    /**
     * Get the project that is showcased.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user (admin) who featured the project.
     */
    public function featuredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'featured_by');
    }
}
