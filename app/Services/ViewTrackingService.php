<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Project;
use App\Models\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class ViewTrackingService
{
    /**
     * Record a public view for a student profile or project.
     *
     * @param Model $viewable The model being viewed (Mahasiswa or Project)
     * @param string|null $ip The visitor's IP address
     * @param string|null $userAgent The visitor's User-Agent string
     * @param string $source The traffic source (direct, qr, internal, external)
     */
    public function recordView(Model $viewable, ?string $ip, ?string $userAgent, string $source): void
    {
        // 1. Determine Mahasiswa ID and skip self-viewing
        $mahasiswaId = null;
        if ($viewable instanceof Mahasiswa) {
            $mahasiswaId = $viewable->id;
        } elseif ($viewable instanceof Project) {
            $mahasiswaId = $viewable->mahasiswa_id;
        }

        if (!$mahasiswaId) {
            return;
        }

        // Exclude own views from logging
        if (Auth::check()) {
            $currentMahasiswa = Auth::user()->mahasiswa;
            if ($currentMahasiswa && $currentMahasiswa->id === $mahasiswaId) {
                return;
            }
        }

        // 2. Anti-Spam: Check Session Key Cooldown
        $sessionKey = 'viewed_' . strtolower(class_basename($viewable)) . '_' . $viewable->id;
        if (Session::has($sessionKey)) {
            return;
        }

        // 3. Anti-Spam: Check Visitor Hash and 15-minute DB Cooldown
        $visitorHash = $this->generateVisitorHash($ip, $userAgent);
        
        $recentViewExists = View::where('viewable_type', get_class($viewable))
            ->where('viewable_id', $viewable->id)
            ->where('visitor_hash', $visitorHash)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->exists();

        if ($recentViewExists) {
            // Put in session to avoid database hits for subsequent refreshes
            Session::put($sessionKey, true);
            return;
        }

        // 4. Record the view to the database
        View::create([
            'mahasiswa_id'  => $mahasiswaId,
            'viewable_type' => get_class($viewable),
            'viewable_id'   => $viewable->id,
            'visitor_hash'  => $visitorHash,
            'source'        => $source,
        ]);

        // 5. Set session flag
        Session::put($sessionKey, true);

        // 6. Invalidate student's analytics dashboard cache
        $cacheKey = "analytics_dashboard_{$mahasiswaId}";
        Cache::forget($cacheKey);
    }

    /**
     * Generate a SHA-256 fingerprint for a visitor.
     */
    protected function generateVisitorHash(?string $ip, ?string $userAgent): string
    {
        $rawString = ($ip ?? '0.0.0.0') . '|' . ($userAgent ?? 'Unknown-Agent');
        return hash('sha256', $rawString);
    }
}
