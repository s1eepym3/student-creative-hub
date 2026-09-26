<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectRevision;
use App\Services\AuditLogService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectRevisionController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService,
        protected ProjectService $projectService
    ) {}

    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        
        $revisions = ProjectRevision::with(['project.mahasiswa', 'category'])
            ->where('status', $status)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.revisions.index', compact('revisions', 'status'));
    }

    public function show(ProjectRevision $revision)
    {
        $revision->load(['project.mahasiswa', 'category', 'mediaFiles', 'technologies']);
        $originalProject = $revision->project->load(['category', 'mediaFiles', 'technologies']);

        return view('admin.revisions.show', compact('revision', 'originalProject'));
    }

    public function approve(ProjectRevision $revision)
    {
        if ($revision->status !== 'pending') {
            abort(400, 'Revision is not pending.');
        }

        DB::beginTransaction();
        try {
            // Apply revision to original project
            $project = $revision->project;
            
            $updateData = [
                'category_id' => $revision->category_id,
                'judul' => $revision->judul,
                'deskripsi' => $revision->deskripsi,
                'project_url' => $revision->project_url,
                'github_url' => $revision->github_url,
                'visibility' => $revision->visibility,
            ];

            // Handle thumbnail replacement if it was changed
            if ($revision->thumbnail && $revision->thumbnail !== $project->thumbnail) {
                app(\App\Services\FileService::class)->delete($project->thumbnail);
                $updateData['thumbnail'] = $revision->thumbnail;
            }

            $project->update($updateData);

            // Replace technologies
            $project->technologies()->delete();
            foreach ($revision->technologies as $tech) {
                $project->technologies()->create([
                    'technology_name' => $tech->technology_name
                ]);
            }

            // Replace media files
            // First, delete old media physically
            foreach ($project->mediaFiles as $media) {
                app(\App\Services\FileService::class)->delete($media->file_path);
            }
            $project->mediaFiles()->delete();

            // Then add new media
            foreach ($revision->mediaFiles as $revMedia) {
                $project->mediaFiles()->create([
                    'file_name' => $revMedia->file_name,
                    'file_path' => $revMedia->file_path,
                    'file_type' => $revMedia->file_type,
                ]);
            }

            // Mark revision as approved
            $revision->update([
                'status' => 'approved',
                'rejection_reason' => null
            ]);

            $this->auditLogService->log(
                'PROJECT_REVISION_APPROVED',
                "Admin menyetujui revisi project: '{$project->judul}' milik {$project->mahasiswa->nama_lengkap}"
            );

            DB::commit();

            return redirect()->route('admin.revisions.index')->with('success', 'Revisi project berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyetujui revisi: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, ProjectRevision $revision)
    {
        if ($revision->status !== 'pending') {
            abort(400, 'Revision is not pending.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000'
        ]);

        $revision->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        $this->auditLogService->log(
            'PROJECT_REVISION_REJECTED',
            "Admin menolak revisi project: '{$revision->judul}' milik {$revision->project->mahasiswa->nama_lengkap}. Alasan: {$request->rejection_reason}"
        );

        return redirect()->route('admin.revisions.index')->with('success', 'Revisi project berhasil ditolak.');
    }
}
