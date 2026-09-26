@extends('layouts.app')

@section('content')
<div class="ds-certificates container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            {{-- Header/Title --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="ds-page-title">Sertifikat Saya</h2>
                    <p class="ds-page-subtitle">Kelola bukti sertifikasi akademis dan profesional Anda.</p>
                </div>
                <a href="{{ route('mahasiswa.certificates.create') }}" class="ds-btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Sertifikat
                </a>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="ds-alert ds-alert-success mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            {{-- Card Container --}}
            <div class="ds-card">
                <div class="ds-card-body p-0">
                    @if($certificates->count() > 0)
                        <div class="table-responsive">
                            <table class="table ds-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Penyelenggara</th>
                                        <th style="width: 100px;">Tahun</th>
                                        <th style="width: 120px; text-align: center;">Dokumen</th>
                                        <th style="width: 150px; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($certificates as $index => $certificate)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $certificate->nama_kegiatan }}</strong></td>
                                        <td>{{ $certificate->penyelenggara }}</td>
                                        <td>{{ $certificate->tahun }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('mahasiswa.certificates.show', $certificate) }}" class="ds-btn-ghost ds-btn-sm">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Lihat
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('mahasiswa.certificates.edit', $certificate) }}" class="ds-icon-btn" title="Edit Sertifikat">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('mahasiswa.certificates.destroy', $certificate) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus sertifikat ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="ds-icon-btn ds-icon-btn-danger" title="Hapus Sertifikat">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="ds-empty-state text-center py-5">
                            <i class="bi bi-patch-check ds-empty-icon mb-3"></i>
                            <h5 class="ds-empty-title">Sertifikat Masih Kosong</h5>
                            <p class="ds-empty-desc mb-4">Tambahkan sertifikat seminar, pelatihan, atau kompetensi untuk memperkuat portofolio Anda.</p>
                            <a href="{{ route('mahasiswa.certificates.create') }}" class="ds-btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Sertifikat Pertama
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
.ds-certificates {
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

/* ── Card & Table ── */
.ds-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
}
.ds-table {
    margin-bottom: 0 !important;
    background-color: var(--surface-primary) !important;
    min-width: 750px;
}
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.ds-table th {
    background-color: var(--bg-section) !important;
    color: var(--text-primary) !important;
    font-family: var(--font-heading);
    font-weight: 600;
    border-bottom: 1px solid var(--border-soft) !important;
    padding: 14px 20px !important;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.ds-table td {
    border-bottom: 1px solid var(--border-soft) !important;
    padding: 14px 20px !important;
    color: var(--text-secondary) !important;
    vertical-align: middle;
}

/* ── Empty State ── */
.ds-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
}
.ds-empty-icon {
    font-size: 2.5rem;
    color: var(--border-soft);
}
.ds-empty-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.15rem;
    color: var(--text-primary);
    margin: 0 0 6px 0;
}
.ds-empty-desc {
    font-size: 0.875rem;
    color: var(--text-secondary);
    max-width: 400px;
}

/* ── Icon Buttons ── */
.ds-icon-btn {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    border-radius: 10px;
    color: var(--text-secondary);
    font-size: 0.90rem;
    text-decoration: none;
    transition: all 150ms ease-out;
    cursor: pointer;
}
.ds-icon-btn:hover {
    background-color: var(--surface-hover);
    color: var(--color-primary);
    border-color: var(--color-primary);
}
.ds-icon-btn-danger:hover {
    background-color: #fdf0f0;
    color: #e57373;
    border-color: #e57373;
}

/* ── Alerts ── */
.ds-alert {
    padding: 14px 20px;
    border-radius: var(--radius-button);
    font-size: 0.875rem;
}
.ds-alert-success {
    background-color: var(--color-success);
    color: var(--color-success-text);
    border: 1px solid rgba(46, 125, 50, 0.2);
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
    padding: 10px 20px;
    font-size: 0.875rem;
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
    padding: 6px 14px;
    font-size: 0.8rem;
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
</style>
@endpush

@endsection
