<?php

namespace App\Services;

use App\Models\Mahasiswa;
use Barryvdh\DomPDF\Facade\Pdf;

class PortfolioPdfService
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * Generate the Dompdf instance for a student's portfolio.
     *
     * @param Mahasiswa $mahasiswa
     * @param string $theme Template theme folder name
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generate(Mahasiswa $mahasiswa, string $theme = 'default')
    {
        // 1. Eager load all required relations in a single query (no N+1)
        $mahasiswa->load([
            'user',
            'projects' => function ($q) {
                $q->approved()->with(['category', 'technologies', 'showcase'])->withCount('views');
            },
            'skills' => function ($q) {
                $q->orderBy('nama_skill');
            },
            'certificates' => function ($q) {
                $q->orderBy('tahun', 'desc');
            },
            'achievements' => function ($q) {
                $q->orderBy('tahun', 'desc');
            },
            'qrPortfolio'
        ]);

        // 2. Curate featured projects list (max 5 items)
        // Sorting Priority: 1. Showcase, 2. Most Viewed, 3. Newest
        $featuredProjects = $mahasiswa->projects->sort(function ($a, $b) {
            // Priority 1: Showcase first
            $aShowcase = $a->showcase !== null;
            $bShowcase = $b->showcase !== null;
            if ($aShowcase !== $bShowcase) {
                return $bShowcase <=> $aShowcase;
            }

            // Priority 2: Views count desc
            if ($a->views_count !== $b->views_count) {
                return $b->views_count <=> $a->views_count;
            }

            // Priority 3: Newest first
            return $b->created_at <=> $a->created_at;
        })->take(5);

        // 3. Get Portfolio Completion Score from existing ProfileService
        $score = $this->profileService->completionPercentage($mahasiswa);
        $scoreText = 'Needs Improvement';
        if ($score >= 90) {
            $scoreText = 'Excellent';
        } elseif ($score >= 70) {
            $scoreText = 'Good';
        } elseif ($score >= 50) {
            $scoreText = 'Average';
        }

        // 4. Build Portfolio Summary from loaded collections (no extra queries)
        $portfolioSummary = [
            'total_projects'     => $mahasiswa->projects->count(),
            'total_skills'       => $mahasiswa->skills->count(),
            'total_certificates' => $mahasiswa->certificates->count(),
            'total_achievements' => $mahasiswa->achievements->count(),
        ];

        // 5. Gather data for Blade view
        $data = [
            'mahasiswa'        => $mahasiswa,
            'featuredProjects' => $featuredProjects,
            'portfolioSummary' => $portfolioSummary,
            'score'            => $score,
            'scoreText'        => $scoreText,
            'generatedAt'      => now()->translatedFormat('d F Y'),
        ];

        // 6. Render the PDF via the selected theme
        $pdf = Pdf::loadView("pdf.{$theme}.portfolio", $data)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);

        // 7. Inject PDF document metadata
        $pdf->getDomPDF()->add_info('Title', "Student Portfolio - {$mahasiswa->nama_lengkap}");
        $pdf->getDomPDF()->add_info('Author', 'Student Creative Hub');
        $pdf->getDomPDF()->add_info('Creator', 'Student Creative Hub');
        $pdf->getDomPDF()->add_info('Subject', 'Professional Student Portfolio');

        // Build keywords from skills & technologies — no extra queries needed
        $keywords = $mahasiswa->skills->take(5)->pluck('nama_skill')
            ->concat(
                $mahasiswa->projects
                    ->flatMap(fn($p) => $p->technologies->pluck('technology_name'))
                    ->take(5)
            )
            ->unique()
            ->implode(', ');

        $pdf->getDomPDF()->add_info('Keywords', $keywords ?: 'Portfolio, Student, Creative Hub');

        return $pdf;
    }
}
