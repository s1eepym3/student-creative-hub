<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Services\PortfolioPdfService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PortfolioPdfController extends Controller
{
    public function __construct(protected PortfolioPdfService $portfolioPdfService) {}

    /**
     * Preview the student's portfolio PDF inline in the browser.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function preview(string $slug)
    {
        $mahasiswa = Mahasiswa::where('slug', $slug)->firstOrFail();

        // Check policy permission (supports admin, owner, or guest if configured)
        $this->authorize('downloadPdf', $mahasiswa);

        $theme = config('portfolio.default_pdf_theme', 'default');
        $pdf = $this->portfolioPdfService->generate($mahasiswa, $theme);

        return $pdf->stream("Portfolio_{$mahasiswa->slug}.pdf");
    }

    /**
     * Force download the student's portfolio PDF file.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function download(string $slug)
    {
        $mahasiswa = Mahasiswa::where('slug', $slug)->firstOrFail();

        // Check policy permission (supports admin, owner, or guest if configured)
        $this->authorize('downloadPdf', $mahasiswa);

        $theme = config('portfolio.default_pdf_theme', 'default');
        $pdf = $this->portfolioPdfService->generate($mahasiswa, $theme);

        return $pdf->download("Portfolio_{$mahasiswa->slug}.pdf");
    }

    /**
     * Preview the logged-in student's own portfolio PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function previewOwn()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            abort(404, 'Profil mahasiswa Anda tidak ditemukan.');
        }

        return $this->preview($mahasiswa->slug);
    }

    /**
     * Download the logged-in student's own portfolio PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadOwn()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            abort(404, 'Profil mahasiswa Anda tidak ditemukan.');
        }

        return $this->download($mahasiswa->slug);
    }
}
