<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class View extends Model
{
    /**
     * Disable the updated_at timestamp since view logs are insert-only.
     */
    public const UPDATED_AT = null;

    protected $fillable = [
        'mahasiswa_id',
        'viewable_type',
        'viewable_id',
        'visitor_hash',
        'source',
    ];

    /**
     * Get the student that this view belongs to.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Get the parent viewable model (Mahasiswa or Project).
     */
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}
