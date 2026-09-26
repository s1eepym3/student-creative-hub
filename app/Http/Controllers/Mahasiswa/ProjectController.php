<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\MediaFileRequest;
use App\Http\Requests\Mahasiswa\ProjectRequest;
use App\Models\Category;
use App\Models\MediaFile;
use App\Models\Project;
use App\Services\AuditLogService;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService,
        protected AuditLogService $auditLogService
    ) {}


    public function index(): View
    {
        $projects = Auth::user()->mahasiswa->projects()->with('category')->latest()->paginate(10);
        return view('mahasiswa.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('nama_kategori')->get();
        return view('mahasiswa.projects.create', compact('categories'));
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $data = $request->validated();
        $data['mahasiswa_id'] = $mahasiswa->id;
        $data['status'] = 'draft';

        $technologies = $request->input('technologies', []);

        $project = $this->projectService->store($data, $request->file('thumbnail'), $technologies);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $this->projectService->uploadMedia($project, $file);
            }
        }

        $this->auditLogService->log(
            'PROJECT_CREATED',
            "Mahasiswa '{$mahasiswa->nama_lengkap}' membuat project: '{$project->judul}'"
        );

        return redirect()->route('mahasiswa.projects.index')
            ->with('success', 'Project berhasil disimpan sebagai Draft.');
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        $categories = Category::orderBy('nama_kategori')->get();
        $technologies = $project->technologies->pluck('technology_name')->implode(', ');

        return view('mahasiswa.projects.edit', compact('project', 'categories', 'technologies'));
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $data = $request->validated();
        
        // Ensure status reset to pending if it was rejected
        if ($project->status === 'rejected') {
            $data['status'] = 'pending';
            $data['rejection_reason'] = null; // Clear rejection reason
        }

        $technologies = $request->input('technologies', []);

        $this->projectService->update($project, $data, $request->file('thumbnail'), $technologies);

        $this->auditLogService->log(
            'PROJECT_UPDATED',
            "Mahasiswa memperbarui project: '{$project->judul}'"
        );

        return redirect()->route('mahasiswa.projects.index')
            ->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $title = $project->judul;
        $this->projectService->deleteProject($project);

        $this->auditLogService->log(
            'PROJECT_DELETED',
            "Mahasiswa menghapus project: '{$title}'"
        );

        return redirect()->route('mahasiswa.projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    public function submit(Project $project): RedirectResponse
    {
        $this->authorize('submit', $project);

        $project->update([
            'status' => 'pending',
            'rejection_reason' => null
        ]);

        $this->auditLogService->log(
            'PROJECT_SUBMITTED',
            "Mahasiswa mengajukan project '{$project->judul}' untuk diverifikasi."
        );

        return redirect()->route('mahasiswa.projects.index')
            ->with('success', 'Project berhasil disubmit untuk diverifikasi.');
    }

    // --- Media Management ---

    public function uploadMedia(MediaFileRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        if ($project->mediaFiles()->count() >= 10) {
            return back()->with('error', 'Maksimal 10 file media per project.');
        }

        $this->projectService->uploadMedia($project, $request->file('file_media'));

        return back()->with('success', 'File media berhasil diunggah.');
    }

    public function destroyMedia(Project $project, MediaFile $media): RedirectResponse
    {
        $this->authorize('update', $project);

        if ($media->project_id !== $project->id) {
            abort(404);
        }

        $this->projectService->deleteMedia($media);

        return back()->with('success', 'File media berhasil dihapus.');
    }

    // --- Revision Management ---

    public function createRevision(Project $project): View
    {
        $this->authorize('requestRevision', $project);

        // Check if there is already a pending revision
        if ($project->revisions()->where('status', 'pending')->exists()) {
            abort(400, 'Anda sudah memiliki pengajuan revisi yang sedang diproses.');
        }

        $categories = Category::orderBy('nama_kategori')->get();
        $technologies = $project->technologies->pluck('technology_name')->implode(', ');

        return view('mahasiswa.projects.revision', compact('project', 'categories', 'technologies'));
    }

    public function storeRevision(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('requestRevision', $project);

        if ($project->revisions()->where('status', 'pending')->exists()) {
            return back()->with('error', 'Anda sudah memiliki pengajuan revisi yang sedang diproses.');
        }

        $data = $request->validated();
        $technologies = $request->input('technologies', []);
        
        $revisionData = [
            'project_id' => $project->id,
            'category_id' => $data['category_id'],
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'project_url' => $data['project_url'],
            'github_url' => $data['github_url'],
            'visibility' => $data['visibility'],
            'status' => 'pending',
        ];

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $revisionData['thumbnail'] = $thumbnailPath;
        }

        $revision = \App\Models\ProjectRevision::create($revisionData);

        foreach ($technologies as $tech) {
            if (trim($tech) !== '') {
                $revision->technologies()->create([
                    'technology_name' => trim($tech)
                ]);
            }
        }

        // We also need to copy over existing media files if new ones are not provided, 
        // OR allow them to upload new media. For simplicity based on requirements, 
        // let's copy existing media into revision media. The user hasn't explicitly asked for complex media editing in revision form, 
        // just that they MUST be able to revise them. We'll support uploading new media here to overwrite existing.
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('media', 'public');
                $revision->mediaFiles()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                ]);
            }
        } else {
            // Copy existing media to revision if no new media provided
            foreach ($project->mediaFiles as $media) {
                $revision->mediaFiles()->create([
                    'file_name' => $media->file_name,
                    'file_path' => $media->file_path, // Note: not duplicating the physical file, just the path.
                    'file_type' => $media->file_type,
                ]);
            }
        }

        $this->auditLogService->log(
            'PROJECT_REVISION_REQUESTED',
            "Mahasiswa '{$project->mahasiswa->nama_lengkap}' mengajukan revisi untuk project: '{$project->judul}'"
        );

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Pengajuan revisi project berhasil dikirim dan menunggu persetujuan Admin.');
    }

    // --- Deletion Request ---

    public function requestDelete(\Illuminate\Http\Request $request, Project $project): RedirectResponse
    {
        $this->authorize('requestDelete', $project);

        $request->validate([
            'deletion_reason' => 'required|string|min:10|max:1000'
        ]);

        $project->update([
            'status' => 'deletion_requested',
            'deletion_reason' => $request->deletion_reason,
            'delete_rejection_reason' => null
        ]);

        $this->auditLogService->log(
            'PROJECT_DELETE_REQUESTED',
            "Mahasiswa '{$project->mahasiswa->nama_lengkap}' mengajukan permohonan hapus untuk project: '{$project->judul}'. Alasan: {$request->deletion_reason}"
        );

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Permintaan penghapusan project berhasil dikirim ke Admin.');
    }
}
