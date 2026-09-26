<?php

namespace App\Services;

use App\Models\User;
use App\Models\Mahasiswa;

class UserService
{
    public function __construct(
        protected FileService $fileService,
        protected ProjectService $projectService
    ) {}

    /**
     * Delete all physical files of a user/mahasiswa before soft delete.
     */
    public function cleanFiles(User $user): void
    {
        if ($user->role !== 'mahasiswa') {
            return;
        }

        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa) {
            return;
        }

        // 1. Delete profile photo and CV file
        if ($mahasiswa->foto_profil) {
            $this->fileService->delete($mahasiswa->foto_profil);
            $mahasiswa->foto_profil = null;
        }

        if ($mahasiswa->cv_file) {
            $this->fileService->delete($mahasiswa->cv_file);
            $mahasiswa->cv_file = null;
        }
        $mahasiswa->save();

        // 2. Delete all certificate files and records
        foreach ($mahasiswa->certificates as $cert) {
            $this->fileService->delete($cert->file_sertifikat);
            $cert->delete();
        }

        // 3. Delete all project files (thumbnails + media gallery) and delete/soft delete projects
        foreach ($mahasiswa->projects as $project) {
            // Delete all media files physically and records
            foreach ($project->mediaFiles as $media) {
                $this->projectService->deleteMedia($media);
            }
            // Delete thumbnail
            $this->fileService->delete($project->thumbnail);
            // Delete project technologies
            $project->technologies()->delete();
            // Soft delete project
            $project->delete();
        }

        // 4. Delete QR Code file and record
        $qr = $mahasiswa->qrPortfolio;
        if ($qr) {
            $this->fileService->delete($qr->qr_path);
            $qr->delete();
        }
    }
}
