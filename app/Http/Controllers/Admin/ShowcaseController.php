<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Showcase;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowcaseController extends Controller
{
    public function __construct(protected AuditLogService $auditLogService) {}

    /**
     * Add project to showcase.
     */
    public function store(Project $project)
    {
        if ($project->status !== 'approved') {
            abort(403, 'Hanya project yang sudah disetujui (approved) yang dapat dimasukkan ke Showcase.');
        }

        if ($project->showcase()->exists()) {
            return back()->with('error', 'Project ini sudah ada di Showcase.');
        }

        Showcase::create([
            'project_id' => $project->id,
            'featured_by' => Auth::id(),
            'featured_at' => now(),
        ]);

        $this->auditLogService->log(
            'SHOWCASE_ADDED',
            "Admin menambahkan project '{$project->judul}' ke Campus Showcase."
        );

        return back()->with('success', 'Project berhasil ditambahkan ke Showcase.');
    }

    /**
     * Remove project from showcase.
     */
    public function destroy(Project $project)
    {
        $showcase = $project->showcase;

        if ($showcase) {
            $this->auditLogService->log(
                'SHOWCASE_REMOVED',
                "Admin menghapus project '{$project->judul}' dari Campus Showcase."
            );
            $showcase->delete();
            return back()->with('success', 'Project berhasil dihapus dari Showcase.');
        }

        return back()->with('error', 'Project tidak ditemukan di Showcase.');
    }
}

