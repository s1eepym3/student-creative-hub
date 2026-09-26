@extends('layouts.app')

@section('content')
<div class="ds-certificates-form container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Header/Title --}}
            <div class="mb-4 text-center">
                <h2 class="ds-page-title">Edit Sertifikat</h2>
                <p class="ds-page-subtitle">Perbarui detail atau berkas sertifikat yang telah Anda unggah.</p>
            </div>

            {{-- Card Container --}}
            <div class="ds-card">
                <div class="ds-card-body">
                    <form action="{{ route('mahasiswa.certificates.update', $certificate) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        
                        <div class="mb-4">
                            <label for="nama_kegiatan" class="ds-form-label">Nama Kegiatan</label>
                            <input type="text" name="nama_kegiatan" id="nama_kegiatan"
                                   class="ds-input @error('nama_kegiatan') is-invalid @enderror"
                                   value="{{ old('nama_kegiatan', $certificate->nama_kegiatan) }}" required>
                            @error('nama_kegiatan')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="penyelenggara" class="ds-form-label">Penyelenggara</label>
                            <input type="text" name="penyelenggara" id="penyelenggara"
                                   class="ds-input @error('penyelenggara') is-invalid @enderror"
                                   value="{{ old('penyelenggara', $certificate->penyelenggara) }}" required>
                            @error('penyelenggara')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tahun" class="ds-form-label">Tahun</label>
                            <input type="number" name="tahun" id="tahun"
                                   class="ds-input @error('tahun') is-invalid @enderror"
                                   value="{{ old('tahun', $certificate->tahun) }}" min="2000" max="{{ date('Y') }}" required>
                            @error('tahun')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="file_sertifikat" class="ds-form-label">Berkas Sertifikat</label>
                            
                            @if($certificate->file_sertifikat)
                                <div class="mb-3 p-3 ds-cv-current-box d-flex align-items-center justify-content-between">
                                    <span class="small text-muted"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Sertifikat Terunggah</span>
                                    <a href="{{ route('mahasiswa.certificates.show', $certificate) }}" class="ds-btn-ghost ds-btn-sm">
                                        <i class="bi bi-eye me-1"></i> Lihat Dokumen
                                    </a>
                                </div>
                            @endif
                            
                            <input type="file" name="file_sertifikat" id="file_sertifikat"
                                   class="ds-input @error('file_sertifikat') is-invalid @enderror"
                                   accept="image/jpeg,image/png,application/pdf">
                            @error('file_sertifikat')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                            <div class="ds-hint mt-2">Biarkan kosong jika tidak ingin mengganti berkas saat ini. (Format: JPG, PNG, PDF, maks. 5 MB)</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('mahasiswa.certificates.index') }}" class="ds-btn-ghost">Batal</a>
                            <button type="submit" class="ds-btn-primary">Perbarui Sertifikat</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
/* ── Layout & Typography ── */
.ds-certificates-form {
    font-family: var(--font-body);
}
.ds-page-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.5rem;
    color: var(--text-primary);
    margin: 0;
}
.ds-page-subtitle {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 4px 0 0;
}

/* ── Card ── */
.ds-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
}
.ds-card-body {
    padding: 32px !important;
}

/* ── Forms ── */
.ds-form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
    display: block;
}
.ds-input {
    width: 100%;
    border-radius: var(--radius-input);
    border: 1px solid var(--border-soft);
    background-color: var(--surface-primary);
    color: var(--text-primary);
    padding: 12px 16px;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 150ms ease-out, box-shadow 150ms ease-out;
}
.ds-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(92, 124, 111, 0.15);
}
.ds-cv-current-box {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-input);
}
.ds-hint {
    font-size: 0.78rem;
    color: var(--text-secondary);
}

/* ── Buttons ── */
.ds-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: var(--color-primary);
    color: #ffffff;
    border: none;
    border-radius: var(--radius-button);
    padding: 12px 24px;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 150ms ease-out, transform 150ms ease-out;
}
.ds-btn-primary:hover {
    background-color: #4a6459;
    transform: translateY(-1px);
    color: #ffffff;
}
.ds-btn-ghost {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: transparent;
    color: var(--text-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-button);
    padding: 12px 24px;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 150ms ease-out;
}
.ds-btn-ghost:hover {
    background-color: var(--surface-hover);
    border-color: var(--color-primary);
    color: var(--color-primary);
}
.ds-btn-sm {
    padding: 6px 14px !important;
    font-size: 0.8rem !important;
}
</style>
@endpush

@endsection
