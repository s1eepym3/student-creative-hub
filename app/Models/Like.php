<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'visitor_ip',
    ];

    /**
     * Get the project that was liked.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
