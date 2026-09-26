<?php

namespace App\Policies;

use App\Models\Mahasiswa;
use App\Models\User;

class MahasiswaPolicy
{
    /**
     * Determine whether the user can download or preview the student's portfolio PDF.
     * Type-hinting "?User" allows this policy to handle unauthenticated guest visitors.
     */
    public function downloadPdf(?User $user, Mahasiswa $mahasiswa): bool
    {
        // 1. Admin users have full access to download any portfolio
        if ($user && $user->isAdmin()) {
            return true;
        }

        // 2. Student owners can download their own portfolio PDF
        if ($user && $user->isMahasiswa() && $user->mahasiswa->id === $mahasiswa->id) {
            return true;
        }

        // 3. For public visitors (guests or other logged-in students), check profile status and system settings
        $isProfileActive = $mahasiswa->user && $mahasiswa->user->status === 'active';
        $allowGuestPdf = config('portfolio.allow_guest_pdf_download', true);

        return $isProfileActive && $allowGuestPdf;
    }
}
