<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\CertificateRequest;
use App\Models\Certificate;
use App\Services\FileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function __construct(protected FileService $fileService) {}

    public function index(): View
    {
        $certificates = Auth::user()->mahasiswa->certificates()->orderBy('tahun', 'desc')->get();
        return view('mahasiswa.certificates.index', compact('certificates'));
    }

    public function create(): View
    {
        return view('mahasiswa.certificates.create');
    }

    public function store(CertificateRequest $request): RedirectResponse
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $data = $request->validated();
        $data['mahasiswa_id'] = $mahasiswa->id;
        $data['file_sertifikat'] = $this->fileService->upload(
            $request->file('file_sertifikat'),
            'sertifikat'
        );

        Certificate::create($data);

        return redirect()->route('mahasiswa.certificates.index')
            ->with('success', 'Sertifikat berhasil ditambahkan.');
    }

    public function show(Certificate $certificate): View
    {
        $this->authorizeCertificate($certificate);
        return view('mahasiswa.certificates.show', compact('certificate'));
    }

    public function edit(Certificate $certificate): View
    {
        $this->authorizeCertificate($certificate);
        return view('mahasiswa.certificates.edit', compact('certificate'));
    }

    public function update(CertificateRequest $request, Certificate $certificate): RedirectResponse
    {
        $this->authorizeCertificate($certificate);
        $data = $request->validated();

        if ($request->hasFile('file_sertifikat')) {
            $data['file_sertifikat'] = $this->fileService->replace(
                $certificate->file_sertifikat,
                $request->file('file_sertifikat'),
                'sertifikat'
            );
        }

        $certificate->update($data);

        return redirect()->route('mahasiswa.certificates.index')
            ->with('success', 'Sertifikat berhasil diperbarui.');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $this->authorizeCertificate($certificate);
        $this->fileService->delete($certificate->file_sertifikat);
        $certificate->delete();

        return redirect()->route('mahasiswa.certificates.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }

    private function authorizeCertificate(Certificate $certificate): void
    {
        if ($certificate->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            abort(403, 'Anda tidak memiliki akses ke sertifikat ini.');
        }
    }
}
