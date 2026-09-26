<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id',
        'nim',
        'slug',
        'nama_lengkap',
        'prodi',
        'angkatan',
        'foto_profil',
        'bio',
        'github',
        'linkedin',
        'instagram',
        'cv_file',
    ];

    /**
     * Get the user that owns the mahasiswa profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The skills that belong to the mahasiswa.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'mahasiswa_skills')
            ->withPivot('level')
            ->withTimestamps();
    }

    /**
     * Get the projects for the mahasiswa.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'mahasiswa_id');
    }

    /**
     * Get the certificates for the mahasiswa.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'mahasiswa_id');
    }

    /**
     * Get the achievements for the mahasiswa.
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class, 'mahasiswa_id');
    }

    /**
     * Get the QR portfolio associated with the mahasiswa.
     */
    public function qrPortfolio(): HasOne
    {
        return $this->hasOne(QrPortfolio::class, 'mahasiswa_id');
    }

    /**
     * Get all views associated with the student (both portfolio and project views).
     */
    public function views(): HasMany
    {
        return $this->hasMany(View::class, 'mahasiswa_id');
    }

    /**
     * Get only the views on the student's public portfolio.
     */
    public function portfolioViews(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }
}
