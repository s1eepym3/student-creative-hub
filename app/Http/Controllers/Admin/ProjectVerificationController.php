<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectProjectRequest;
use App\Models\Project;
use App\Services\AuditLogService;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectVerificationController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService,
        protected ProjectService $projectService
    ) {}

    public function index(Request $request): View
    {
        $query = Project::with(['mahasiswa', 'category'])->latest();
        
        $status = $request->input('status', 'pending');
        $query->where('status', $status);

        $projects = $query->paginate(20);
            
        return view('admin.verifications.index', compact('projects'));
    }

    public function show(Project $project): View
    {
        $project->load(['mahasiswa', 'category', 'mediaFiles', 'technologies']);
        return view('admin.verifications.show', compact('project'));
    }

    public function approve(Project $project): RedirectResponse
    {
        if ($project->status !== 'pending') {
            abort(404);
        }

        $project->update([
            'status' => 'approved',
            'rejection_reason' => null
        ]);

        $this->auditLogService->log(
            'PROJECT_APPROVED',
            "Admin menyetujui project: '{$project->judul}' milik {$project->mahasiswa->nama_lengkap}"
        );

        return redirect()->route('admin.verifications.index')
            ->with('success', 'Project berhasil disetujui.');
    }

    public function reject(RejectProjectRequest $request, Project $project): RedirectResponse
    {
        if ($project->status !== 'pending') {
            abort(404);
        }

        $project->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        $this->auditLogService->log(
            'PROJECT_REJECTED',
            "Admin menolak project: '{$project->judul}' milik {$project->mahasiswa->nama_lengkap}. Alasan: {$request->rejection_reason}"
        );

        return redirect()->route('admin.verifications.index')
            ->with('success', 'Project berhasil ditolak.');
    }

    /**
     * Admin soft-deletes a project with reason.
     */
    public function destroy(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'admin_delete_reason' => 'required|string|min:10|max:500',
        ]);

        $title = $project->judul;

        // Delete all physical media files and thumbnail
        foreach ($project->mediaFiles as $media) {
            $this->projectService->deleteMedia($media);
        }
        app(\App\Services\FileService::class)->delete($project->thumbnail);
        
        // Store delete reason before soft deleting
        $project->update(['admin_delete_reason' => $request->admin_delete_reason]);

        // Soft delete the project
        $project->delete();

        $this->auditLogService->log(
            'PROJECT_DELETED_BY_ADMIN',
            "Admin menghapus project: '{$title}'. Alasan: {$request->admin_delete_reason}"
        );

        return redirect()->route('admin.verifications.index')
            ->with('success', "Project '{$title}' berhasil dihapus.");
    }
}
