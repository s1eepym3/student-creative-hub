<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\AuditLogService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectDeletionController extends Controller
{
    public function __construct(
        protected AuditLogService $auditLogService,
        protected ProjectService $projectService
    ) {}

    public function index(Request $request)
    {
        $status = $request->input('status', 'deletion_requested');

        $query = Project::with(['mahasiswa', 'category']);

        if ($status === 'deletion_requested') {
            $query->where('status', 'deletion_requested');
        } else {
            // Show only rejected deletion requests
            $query->where('status', 'approved') // The project goes back to approved
                  ->whereNotNull('delete_rejection_reason');
        }

        $projects = $query->latest('updated_at')->paginate(15)->withQueryString();

        return view('admin.deletions.index', compact('projects', 'status'));
    }

    public function approve(Project $project)
    {
        if ($project->status !== 'deletion_requested') {
            abort(400, 'Project is not requesting deletion.');
        }

        DB::beginTransaction();
        try {
            $title = $project->judul;
            $mahasiswaName = $project->mahasiswa->nama_lengkap;
            $deletionReason = $project->deletion_reason;

            // Delete physical files
            foreach ($project->mediaFiles as $media) {
                $this->projectService->deleteMedia($media);
            }
            app(\App\Services\FileService::class)->delete($project->thumbnail);

            // Keep the admin delete reason to reflect that the admin approved it
            $project->update(['admin_delete_reason' => 'Disetujui dari permintaan penghapusan mahasiswa: ' . $deletionReason]);
            
            // Soft delete
            $project->delete();

            $this->auditLogService->log(
                'PROJECT_DELETE_APPROVED',
                "Admin menyetujui permintaan hapus project: '{$title}' milik {$mahasiswaName}"
            );

            DB::commit();

            return redirect()->route('admin.deletions.index')->with('success', 'Permintaan hapus project disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Project $project)
    {
        if ($project->status !== 'deletion_requested') {
            abort(400, 'Project is not requesting deletion.');
        }

        $request->validate([
            'delete_rejection_reason' => 'required|string|min:10|max:1000'
        ]);

        $project->update([
            'status' => 'approved',
            'delete_rejection_reason' => $request->delete_rejection_reason
        ]);

        $this->auditLogService->log(
            'PROJECT_DELETE_REJECTED',
            "Admin menolak permintaan hapus project: '{$project->judul}' milik {$project->mahasiswa->nama_lengkap}. Alasan: {$request->delete_rejection_reason}"
        );

        return redirect()->route('admin.deletions.index')->with('success', 'Permintaan hapus project ditolak.');
    }
}
