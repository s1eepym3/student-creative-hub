<?php

namespace App\Services;

use App\Models\Mahasiswa;
use Illuminate\Http\UploadedFile;

class ProfileService
{
    public function __construct(protected FileService $fileService) {}

    /**
     * Update mahasiswa profile data, handling file uploads.
     */
    public function update(Mahasiswa $mahasiswa, array $data, ?UploadedFile $photo, ?UploadedFile $cv): Mahasiswa
    {
        if ($photo) {
            $data['foto_profil'] = $this->fileService->replace(
                $mahasiswa->foto_profil,
                $photo,
                'foto_profil'
            );
        }

        if ($cv) {
            $data['cv_file'] = $this->fileService->replace(
                $mahasiswa->cv_file,
                $cv,
                'cv'
            );
        }

        $mahasiswa->update($data);

        return $mahasiswa;
    }

    /**
     * Calculate profile completion percentage based on key fields.
     */
    public function completionPercentage(Mahasiswa $mahasiswa): int
    {
        $fields = ['foto_profil', 'bio', 'github', 'linkedin', 'instagram', 'cv_file'];
        $filled = collect($fields)->filter(fn($f) => !empty($mahasiswa->$f))->count();

        // Check if has at least 1 skill
        $hasSkill = $mahasiswa->skills()->exists() ? 1 : 0;
        
        $totalCriteria = count($fields) + 1; // +1 for the skill check
        $totalFilled = $filled + $hasSkill;

        return (int) round(($totalFilled / $totalCriteria) * 100);
    }
}
