<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct(protected AuditLogService $auditLogService) {}

    /**
     * Toggle like for a project.
     */
    public function toggleLike(Request $request, Project $project)
    {
        if ($project->status !== 'approved' || $project->visibility !== 'public') {
            abort(403, 'Aksi tidak diizinkan. Project belum disetujui atau private.');
        }

        $visitorIp = $request->ip();

        $existingLike = $project->likes()->where('visitor_ip', $visitorIp)->first();

        if ($existingLike) {
            $existingLike->delete();
            $this->auditLogService->log('PROJECT_UNLIKED', "IP {$visitorIp} membatalkan like pada project '{$project->judul}'", null);
            $message = 'Berhasil membatalkan like.';
        } else {
            $project->likes()->create(['visitor_ip' => $visitorIp]);
            $this->auditLogService->log('PROJECT_LIKED', "IP {$visitorIp} menyukai project '{$project->judul}'", null);
            $message = 'Berhasil menyukai project ini.';
        }

        return back()->with('success', $message);
    }
}

