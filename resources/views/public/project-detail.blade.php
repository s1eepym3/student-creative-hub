@extends('layouts.app')

@section('content')
<div class="ds-project-detail container py-5">
    
    {{-- Breadcrumbs --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb ds-breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('public.explore') }}">Explore</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $project->judul }}</li>
        </ol>
    </nav>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="ds-alert ds-alert-success mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="ds-alert ds-alert-danger mb-4" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        {{-- Main Project Content --}}
        <div class="col-lg-8">
            <div class="ds-card ds-detail-card mb-4">
                <div class="ds-banner-container">
                    @if($project->thumbnail)
                        <img src="{{ asset('storage/' . $project->thumbnail) }}" class="ds-detail-img" alt="Thumbnail" loading="lazy">
                    @else
                        <div class="ds-detail-placeholder">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>
                <div class="ds-card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                        <div>
                            <span class="ds-category-badge mb-2 d-inline-block">{{ $project->category->nama_kategori }}</span>
                            <h2 class="ds-detail-title mb-0">{{ $project->judul }}</h2>
                        </div>
                        <form action="{{ route('public.project.like', $project) }}" method="POST">
                            @csrf
                            @if($hasLiked)
                                <button type="submit" class="ds-btn-danger ds-btn-sm rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-heart-fill me-1"></i> Liked ({{ $project->likes_count }})
                                </button>
                            @else
                                <button type="submit" class="ds-btn-ghost ds-btn-sm rounded-pill px-3 shadow-sm text-danger border-danger-subtle">
                                    <i class="bi bi-heart me-1"></i> Like ({{ $project->likes_count }})
                                </button>
                            @endif
                        </form>
                    </div>

                    {{-- Actions/Links --}}
                    <div class="ds-project-links mb-4">
                        @if($project->project_url)
                            <a href="{{ $project->project_url }}" target="_blank" class="ds-btn-primary ds-btn-sm me-2">
                                <i class="bi bi-link-45deg me-1"></i> Live Demo
                            </a>
                        @endif
                        @if($project->github_url)
                            <a href="{{ $project->github_url }}" target="_blank" class="ds-btn-ghost ds-btn-sm">
                                <i class="bi bi-github me-1"></i> Repository
                            </a>
                        @endif
                    </div>

                    {{-- Description --}}
                    <h5 class="ds-section-title border-bottom pb-2 mb-3">Deskripsi Proyek</h5>
                    <p class="ds-detail-desc text-break">{{ $project->deskripsi }}</p>

                    {{-- Technologies Used --}}
                    @if($project->technologies->count() > 0)
                        <h5 class="ds-section-title border-bottom pb-2 mt-4 mb-3">Teknologi</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($project->technologies as $tech)
                                <span class="ds-tech-tag">{{ $tech->technology_name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Gallery Media --}}
            @if($project->mediaFiles->count() > 0)
                <h4 class="ds-section-title mb-3">Galeri Media</h4>
                <div class="row g-3 mb-4">
                    @foreach($project->mediaFiles as $media)
                        <div class="col-md-6">
                            <div class="ds-card ds-gallery-card h-100">
                                @if(str_starts_with($media->file_type, 'image/'))
                                    <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank" class="d-block ds-gallery-img-link">
                                        <img src="{{ asset('storage/' . $media->file_path) }}" class="ds-gallery-media" alt="Media" loading="lazy">
                                    </a>
                                @elseif(str_starts_with($media->file_type, 'video/'))
                                    <video src="{{ asset('storage/' . $media->file_path) }}" class="ds-gallery-media" controls></video>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Author Sidebar --}}
        <div class="col-lg-4">
            <div class="ds-sidebar-card sticky-top" style="top: 24px; z-index: 10;">
                <div class="text-center p-4">
                    <img src="{{ $project->mahasiswa->foto_profil ? asset('storage/' . $project->mahasiswa->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($project->mahasiswa->nama_lengkap) }}" 
                         alt="Profile" class="ds-sidebar-avatar mb-3" loading="lazy">
                    <h5 class="ds-profile-name mb-1">{{ $project->mahasiswa->nama_lengkap }}</h5>
                    <p class="ds-profile-sub mb-3">{{ $project->mahasiswa->prodi }} <span class="ds-bullet">·</span> Angkatan {{ $project->mahasiswa->angkatan }}</p>
                    
                    <a href="{{ route('public.profile', $project->mahasiswa->slug) }}" class="ds-btn-primary w-100 mb-3 text-center d-block">
                        Lihat Profil Lengkap
                    </a>

                    <div class="d-flex justify-content-center gap-3 ds-social-links">
                        @if($project->mahasiswa->github)
                            <a href="{{ $project->mahasiswa->github }}" target="_blank" title="GitHub" class="ds-social-link"><i class="bi bi-github"></i></a>
                        @endif
                        @if($project->mahasiswa->linkedin)
                            <a href="{{ $project->mahasiswa->linkedin }}" target="_blank" title="LinkedIn" class="ds-social-link"><i class="bi bi-linkedin"></i></a>
                        @endif
                        @if($project->mahasiswa->instagram)
                            <a href="{{ $project->mahasiswa->instagram }}" target="_blank" title="Instagram" class="ds-social-link"><i class="bi bi-instagram"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ── Layout & Typography ── */
.ds-project-detail {
    font-family: var(--font-body);
}
.ds-breadcrumb {
    background: transparent;
    padding: 0;
    font-size: 0.85rem;
}
.ds-breadcrumb a {
    color: var(--text-secondary);
    text-decoration: none;
}
.ds-breadcrumb a:hover {
    color: var(--color-primary);
}
.ds-breadcrumb .breadcrumb-item.active {
    color: var(--text-primary);
    font-weight: 500;
}
.ds-detail-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.6rem;
    color: var(--text-primary);
}
.ds-section-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--text-primary);
}
.ds-detail-desc {
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.7;
    white-space: pre-line;
}

/* ── Banner & Images ── */
.ds-banner-container {
    width: 100%;
    aspect-ratio: 21/9;
    overflow: hidden;
    border-top-left-radius: var(--radius-card);
    border-top-right-radius: var(--radius-card);
}
.ds-detail-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.ds-detail-placeholder {
    width: 100%;
    height: 100%;
    background-color: var(--surface-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: var(--text-secondary);
    opacity: 0.4;
}

/* ── Cards & Overrides ── */
.ds-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
}
.ds-detail-card {
    background-color: var(--surface-project) !important;
}

/* ── Category & Tags ── */
.ds-category-badge {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    color: var(--text-primary);
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 500;
}
.ds-tech-tag {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    color: var(--text-secondary);
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 500;
}

/* ── Sidebar Styles ── */
.ds-sidebar-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
}
.ds-sidebar-avatar {
    width: 100px;
    height: 100px;
    border-radius: 20px;
    object-fit: cover;
    border: 3px solid var(--surface-secondary);
}
.ds-profile-name {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.15rem;
    color: var(--text-primary);
}
.ds-profile-sub {
    font-size: 0.82rem;
    color: var(--text-secondary);
}
.ds-bullet {
    margin: 0 6px;
    opacity: 0.4;
}

/* ── Social Links ── */
.ds-social-links {}
.ds-social-link {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: var(--bg-section);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--text-primary) !important;
    text-decoration: none;
    font-size: 1rem;
    transition: all 150ms ease-out;
}
.ds-social-link:hover {
    background-color: var(--surface-hover);
    color: var(--color-primary) !important;
    transform: scale(1.08);
}

/* ── Gallery Media ── */
.ds-gallery-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: 16px;
    overflow: hidden;
    transition: transform 150ms ease-out;
}
.ds-gallery-card:hover {
    transform: scale(1.015);
}
.ds-gallery-img-link {
    display: block;
    width: 100%;
}
.ds-gallery-media {
    width: 100%;
    aspect-ratio: 4/3;
    object-fit: cover;
}

/* ── Alert Styles ── */
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
.ds-alert-danger {
    background-color: var(--color-danger);
    color: var(--color-danger-text);
    border: 1px solid rgba(229, 115, 115, 0.2);
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
.ds-btn-danger {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #e57373;
    color: #ffffff;
    border: none;
    border-radius: var(--radius-button);
    padding: 10px 20px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 150ms ease-out;
}
.ds-btn-danger:hover {
    background-color: #d32f2f;
    color: #ffffff;
}
.ds-btn-sm {
    padding: 6px 14px !important;
    font-size: 0.8rem !important;
}
</style>
@endpush

@endsection
