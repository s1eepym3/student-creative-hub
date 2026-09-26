@extends('layouts.app')

@section('content')
<div class="ds-certificates-show container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Header/Title --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="ds-page-title">Detail Sertifikat</h2>
                    <p class="ds-page-subtitle">Informasi lengkap sertifikasi Anda yang terunggah.</p>
                </div>
                <a href="{{ route('mahasiswa.certificates.index') }}" class="ds-btn-ghost ds-btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            {{-- Card Container --}}
            <div class="ds-card">
                <div class="ds-card-body">
                    
                    {{-- Detail Information --}}
                    <div class="ds-info-grid mb-4">
                        <div class="row py-2 ds-info-row">
                            <div class="col-sm-3 fw-semibold text-dark">Nama Kegiatan</div>
                            <div class="col-sm-9 text-secondary">{{ $certificate->nama_kegiatan }}</div>
                        </div>
                        <div class="row py-2 ds-info-row">
                            <div class="col-sm-3 fw-semibold text-dark">Penyelenggara</div>
                            <div class="col-sm-9 text-secondary">{{ $certificate->penyelenggara }}</div>
                        </div>
                        <div class="row py-2 ds-info-row">
                            <div class="col-sm-3 fw-semibold text-dark">Tahun</div>
                            <div class="col-sm-9 text-secondary">{{ $certificate->tahun }}</div>
                        </div>
                    </div>

                    {{-- Document Viewer Section --}}
                    @if($certificate->file_sertifikat)
                        <div class="ds-divider my-4"></div>
                        <h6 class="ds-section-title mb-3"><i class="bi bi-file-earmark-text text-success me-2"></i>Berkas Lampiran</h6>
                        
                        @php
                            $ext = strtolower(pathinfo($certificate->file_sertifikat, PATHINFO_EXTENSION));
                        @endphp
                        
                        <div class="ds-document-preview-box mb-4">
                            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                <div class="text-center">
                                    <img src="{{ Storage::url($certificate->file_sertifikat) }}" alt="Certificate" class="ds-preview-img img-fluid">
                                </div>
                            @elseif($ext === 'pdf')
                                <div class="ds-pdf-container">
                                    <iframe src="{{ Storage::url($certificate->file_sertifikat) }}" class="ds-pdf-iframe"></iframe>
                                </div>
                            @else
                                <div class="text-center p-4">
                                    <i class="bi bi-file-earmark-arrow-down display-4 text-muted mb-2"></i>
                                    <p class="small text-muted">Pratinjau tidak tersedia untuk tipe file ini.</p>
                                </div>
                            @endif
                        </div>

                        <div class="text-center">
                            <a href="{{ Storage::url($certificate->file_sertifikat) }}" download class="ds-btn-primary">
                                <i class="bi bi-download me-2"></i> Unduh Sertifikat
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
/* ── Layout & Typography ── */
.ds-certificates-show {
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
.ds-section-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1rem;
    color: var(--text-primary);
}

/* ── Divider ── */
.ds-divider {
    border-top: 1px solid var(--border-soft);
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

/* ── Detail Info Grid ── */
.ds-info-grid {
    background-color: var(--surface-secondary);
    border-radius: var(--radius-input);
    padding: 16px 20px;
    border: 1px solid var(--border-soft);
}
.ds-info-row {
    border-bottom: 1px solid rgba(216, 207, 196, 0.2);
}
.ds-info-row:last-child {
    border-bottom: none;
}

/* ── Document Preview Box ── */
.ds-document-preview-box {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-input);
    overflow: hidden;
    padding: 12px;
}
.ds-preview-img {
    border-radius: var(--radius-input);
    border: 1px solid var(--border-soft);
    max-height: 480px;
    box-shadow: var(--shadow-soft);
}
.ds-pdf-container {
    width: 100%;
    height: 520px;
    border-radius: var(--radius-input);
    overflow: hidden;
    border: 1px solid var(--border-soft);
}
.ds-pdf-iframe {
    width: 100%;
    height: 100%;
    border: none;
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
    padding: 8px 16px;
    font-size: 0.85rem;
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
