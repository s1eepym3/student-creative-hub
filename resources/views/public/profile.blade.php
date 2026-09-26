@extends('layouts.app')

@section('content')
<div class="ds-profile-page container py-5">
    <div class="row g-4">
        <!-- Sidebar Biodata -->
        <div class="col-lg-4">
            <div class="ds-sidebar-card sticky-top" style="top: 24px; z-index: 10;">
                <div class="text-center p-4">
                    <img src="{{ $mahasiswa->foto_profil ? asset('storage/' . $mahasiswa->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($mahasiswa->nama_lengkap) }}" 
                         alt="Profile" class="ds-profile-avatar mb-3" loading="lazy">
                    <h4 class="ds-profile-name mb-1">{{ $mahasiswa->nama_lengkap }}</h4>
                    <p class="ds-profile-sub mb-3">{{ $mahasiswa->prodi }} <span class="ds-bullet">·</span> Angkatan {{ $mahasiswa->angkatan }}</p>
                    
                    <div class="d-flex justify-content-center gap-3 mb-4 ds-social-links">
                        @if($mahasiswa->github)
                            <a href="{{ $mahasiswa->github }}" target="_blank" title="GitHub" class="ds-social-link"><i class="bi bi-github"></i></a>
                        @endif
                        @if($mahasiswa->linkedin)
                            <a href="{{ $mahasiswa->linkedin }}" target="_blank" title="LinkedIn" class="ds-social-link"><i class="bi bi-linkedin"></i></a>
                        @endif
                        @if($mahasiswa->instagram)
                            <a href="{{ $mahasiswa->instagram }}" target="_blank" title="Instagram" class="ds-social-link"><i class="bi bi-instagram"></i></a>
                        @endif
                    </div>

                    @if($mahasiswa->bio)
                        <div class="text-start mb-4">
                            <h6 class="ds-sidebar-section-title">Tentang Saya</h6>
                            <p class="ds-profile-bio" style="white-space: pre-line;">{{ $mahasiswa->bio }}</p>
                        </div>
                    @endif

                    @if($mahasiswa->cv_file)
                        <div class="d-grid mb-3">
                            <a href="{{ asset('storage/' . $mahasiswa->cv_file) }}" target="_blank" class="ds-btn-primary">
                                <i class="bi bi-download me-2"></i>Unduh CV / Resume
                            </a>
                        </div>
                    @endif

                    {{-- Portfolio PDF Download & Preview --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('public.profile.pdf', $mahasiswa->slug) }}"
                           class="ds-btn-ghost"
                           title="Download Portfolio PDF">
                            <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
                        </a>
                        <a href="{{ route('public.profile.pdf_preview', $mahasiswa->slug) }}"
                           target="_blank"
                           class="ds-btn-ghost"
                           title="Preview Portfolio PDF">
                            <i class="bi bi-eye me-2"></i>Preview PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Skills -->
            @if($mahasiswa->skills->count() > 0)
                <div class="ds-card-section mb-4">
                    <div class="ds-section-body">
                        <h5 class="ds-content-title mb-3"><i class="bi bi-stars text-warning me-2"></i>Keahlian</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($mahasiswa->skills as $skill)
                                <span class="ds-skill-badge">{{ $skill->nama_skill }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Achievements -->
            @if($mahasiswa->achievements->count() > 0)
                <div class="ds-card-section mb-4">
                    <div class="ds-section-body">
                        <h5 class="ds-content-title mb-4"><i class="bi bi-trophy text-warning me-2"></i>Prestasi &amp; Pencapaian</h5>
                        <div class="row g-3">
                            @foreach($mahasiswa->achievements as $achievement)
                                <div class="col-md-6">
                                    <div class="ds-collectible-badge">
                                        <div class="ds-badge-ribbon"></div>
                                        <div class="ds-collectible-body">
                                            <h6 class="ds-badge-title mb-1">{{ $achievement->judul }}</h6>
                                            <p class="ds-badge-meta mb-2">
                                                <span>{{ $achievement->level }}</span>
                                                <span class="ds-bullet">·</span>
                                                <span>{{ $achievement->tahun }}</span>
                                            </p>
                                            @if($achievement->deskripsi)
                                                <p class="ds-badge-desc mb-0">{{ $achievement->deskripsi }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Certificates -->
            @if($mahasiswa->certificates->count() > 0)
                <div class="ds-card-section mb-4">
                    <div class="ds-section-body">
                        <h5 class="ds-content-title mb-4"><i class="bi bi-patch-check text-success me-2"></i>Sertifikasi</h5>
                        <div class="row g-3">
                            @foreach($mahasiswa->certificates as $certificate)
                                <div class="col-md-6">
                                    <div class="ds-printed-doc">
                                        <div class="ds-printed-header">
                                            <div class="ds-doc-stamp"><i class="bi bi-award"></i></div>
                                            <div>
                                                <h6 class="ds-doc-title mb-1">{{ $certificate->nama_kegiatan }}</h6>
                                                <p class="ds-doc-org mb-1">{{ $certificate->penyelenggara }}</p>
                                                <p class="ds-doc-year mb-0">Tahun: {{ $certificate->tahun }}</p>
                                            </div>
                                        </div>
                                        @if($certificate->file_sertifikat)
                                            <div class="mt-3 text-end">
                                                <a href="{{ asset('storage/' . $certificate->file_sertifikat) }}" target="_blank" class="ds-btn-ghost ds-btn-sm">
                                                    Lihat Dokumen
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Portfolios -->
            <div class="mt-5">
                <h5 class="ds-content-title mb-4">Portofolio &amp; Karya ({{ $mahasiswa->projects->count() }})</h5>
                
                @if($mahasiswa->projects->isEmpty())
                    <div class="ds-empty-state-card text-center p-5">
                        <i class="bi bi-folder2-open ds-empty-icon mb-3"></i>
                        <p class="ds-empty-text">Belum ada karya publik yang dipublikasikan.</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($mahasiswa->projects as $project)
                            <div class="col-md-6">
                                <a href="{{ route('public.project_detail', $project->slug) }}" class="text-decoration-none ds-portfolio-item-link">
                                    <div class="ds-portfolio-card">
                                        <div class="ds-img-container">
                                            @if($project->thumbnail)
                                                <img src="{{ asset('storage/' . $project->thumbnail) }}" class="ds-portfolio-img" alt="Thumbnail" loading="lazy">
                                            @else
                                                <div class="ds-portfolio-placeholder">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                            <span class="ds-category-badge">{{ $project->category->nama_kategori }}</span>
                                        </div>
                                        <div class="ds-portfolio-body">
                                            <h5 class="ds-portfolio-title text-truncate" title="{{ $project->judul }}">{{ $project->judul }}</h5>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span class="ds-likes"><i class="bi bi-heart-fill me-1"></i> {{ $project->likes_count }}</span>
                                                <span class="ds-btn-read">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ── Layout & Backgrounds ── */
.ds-profile-page {
    font-family: var(--font-body);
}
.ds-bullet {
    margin: 0 6px;
    opacity: 0.4;
}

/* ── Sidebar Biodata ── */
.ds-sidebar-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
}
.ds-profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 24px;
    object-fit: cover;
    border: 3px solid var(--surface-secondary);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
}
.ds-profile-name {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.35rem;
    color: var(--text-primary);
}
.ds-profile-sub {
    font-size: 0.85rem;
    color: var(--text-secondary);
}
.ds-sidebar-section-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-soft);
    padding-bottom: 8px;
}
.ds-profile-bio {
    font-size: 0.85rem;
    color: var(--text-secondary);
    line-height: 1.6;
}

/* ── Social Links ── */
.ds-social-links {}
.ds-social-link {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background-color: var(--bg-section);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--text-primary) !important;
    text-decoration: none;
    font-size: 1.1rem;
    transition: all 150ms ease-out;
}
.ds-social-link:hover {
    background-color: var(--surface-hover);
    color: var(--color-primary) !important;
    transform: scale(1.08);
}

/* ── Main Content Styles ── */
.ds-content-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.2rem;
    color: var(--text-primary);
}
.ds-card-section {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
}
.ds-section-body {
    padding: 24px;
}

/* ── Skills ── */
.ds-skill-badge {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    color: var(--text-primary);
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 150ms ease-out;
}
.ds-skill-badge:hover {
    background-color: var(--surface-hover);
    border-color: var(--color-primary);
    color: var(--color-primary);
}

/* ── Achievements (Collectible Badges) ── */
.ds-collectible-badge {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    border-radius: 18px;
    overflow: hidden;
    position: relative;
    height: 100%;
    display: flex;
}
.ds-badge-ribbon {
    width: 6px;
    background-color: var(--color-secondary);
    flex-shrink: 0;
}
.ds-collectible-body {
    padding: 16px 20px;
    flex: 1;
}
.ds-badge-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--text-primary);
}
.ds-badge-meta {
    font-size: 0.78rem;
    color: var(--text-secondary);
}
.ds-badge-desc {
    font-size: 0.82rem;
    color: var(--text-secondary);
    line-height: 1.5;
}

/* ── Certificates (Printed Paper feel) ── */
.ds-printed-doc {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(216, 207, 196, 0.2);
    position: relative;
    height: 100%;
}
.ds-printed-header {
    display: flex;
    align-items: start;
    gap: 16px;
}
.ds-doc-stamp {
    width: 42px;
    height: 42px;
    background-color: var(--color-success);
    color: var(--color-success-text);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.ds-doc-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--text-primary);
}
.ds-doc-org {
    font-size: 0.8rem;
    color: var(--text-secondary);
}
.ds-doc-year {
    font-size: 0.78rem;
    color: var(--text-secondary);
}

/* ── Portfolios & Project Cards ── */
.ds-portfolio-card {
    background-color: var(--surface-project);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
    height: 100%;
    transition: transform 150ms ease-out, box-shadow 150ms ease-out;
}
.ds-portfolio-card:hover {
    transform: scale(1.015);
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
}
.ds-img-container {
    position: relative;
    width: 100%;
    aspect-ratio: 16/9;
    overflow: hidden;
}
.ds-portfolio-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 300ms ease-out;
}
.ds-portfolio-card:hover .ds-portfolio-img {
    transform: scale(1.03);
}
.ds-portfolio-placeholder {
    width: 100%;
    height: 100%;
    background-color: var(--surface-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--text-secondary);
    opacity: 0.4;
}
.ds-category-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background-color: rgba(252, 250, 248, 0.9);
    border: 1px solid var(--border-soft);
    color: var(--text-primary);
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}
.ds-portfolio-body {
    padding: 20px;
}
.ds-portfolio-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.05rem;
    color: var(--text-primary);
    margin: 0;
}
.ds-likes {
    font-size: 0.8rem;
    color: #e57373;
    font-weight: 500;
}
.ds-btn-read {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--color-primary);
}
.ds-portfolio-item-link:hover .ds-btn-read {
    color: var(--color-secondary);
}

/* ── Empty State ── */
.ds-empty-state-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    color: var(--text-secondary);
}
.ds-empty-icon {
    font-size: 2.5rem;
    opacity: 0.3;
}
.ds-empty-text {
    font-size: 0.9rem;
    margin: 0;
}

/* ── Custom Buttons ── */
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
    padding: 10px 20px;
    font-size: 0.875rem;
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
    padding: 5px 12px !important;
    font-size: 0.78rem !important;
}
</style>
@endpush

@endsection
