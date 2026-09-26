@extends('layouts.app')

@section('content')
<div class="ds-showcase container py-5">
    
    {{-- Header Banner --}}
    <div class="row mb-5 text-center">
        <div class="col-lg-8 mx-auto ds-header-banner">
            <span class="ds-badge-top mb-3"><i class="bi bi-award me-1"></i> Curator's Selection</span>
            <h1 class="ds-hero-title mb-3">Campus Showcase</h1>
            <p class="ds-hero-lead mb-0">Kumpulan karya-karya terbaik, inovatif, dan membanggakan dari mahasiswa Universitas Malikussaleh yang telah dikurasi secara ketat oleh pihak kampus.</p>
        </div>
    </div>

    {{-- Showcase Grid --}}
    @if($showcases->isEmpty())
        <div class="ds-empty-state-card text-center p-5 mt-4">
            <i class="bi bi-award ds-empty-icon mb-3"></i>
            <p class="ds-empty-title">Showcase Masih Kosong</p>
            <p class="ds-empty-text">Belum ada karya pilihan yang dimasukkan oleh kurator kampus.</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($showcases as $showcase)
                <div class="col-md-6 col-lg-4">
                    <div class="ds-project-card d-flex flex-column h-100">
                        <div class="position-relative ds-img-wrapper">
                            @if($showcase->project->thumbnail)
                                <img src="{{ asset('storage/' . $showcase->project->thumbnail) }}" class="ds-project-img" alt="Thumbnail" loading="lazy">
                            @else
                                <div class="ds-project-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 m-3 ds-badge-featured">
                                <i class="bi bi-star"></i> Featured
                            </span>
                        </div>
                        <div class="ds-card-body d-flex flex-column flex-grow-1">
                            <div class="mb-2">
                                <span class="ds-category-badge">{{ $showcase->project->category->nama_kategori }}</span>
                            </div>
                            <h4 class="ds-project-title text-truncate mb-2" title="{{ $showcase->project->judul }}">
                                <a href="{{ route('public.project_detail', $showcase->project->slug) }}" class="text-decoration-none text-dark">
                                    {{ $showcase->project->judul }}
                                </a>
                            </h4>
                            <p class="ds-project-author mb-4">
                                Karya dari: <a href="{{ route('public.profile', $showcase->project->mahasiswa->slug) }}" class="ds-author-link">{{ $showcase->project->mahasiswa->nama_lengkap }}</a>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 ds-card-footer-line">
                                <span class="ds-likes"><i class="bi bi-heart-fill me-1"></i> {{ $showcase->project->likes_count }} Likes</span>
                                <a href="{{ route('public.project_detail', $showcase->project->slug) }}" class="ds-btn-ghost ds-btn-sm">Lihat Mahakarya</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-5">
            {{ $showcases->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
/* ── Layout & Headers ── */
.ds-header-banner {
    padding: 32px 0;
}
.ds-badge-top {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--color-secondary);
}
.ds-hero-title {
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2.75rem;
    color: var(--text-primary);
}
.ds-hero-lead {
    font-size: 1.05rem;
    color: var(--text-secondary);
    line-height: 1.6;
}

/* ── Project Cards ── */
.ds-project-card {
    background-color: var(--surface-project);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
    transition: transform 150ms ease-out, box-shadow 150ms ease-out;
}
.ds-project-card:hover {
    transform: scale(1.015);
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
}
.ds-img-wrapper {
    width: 100%;
    aspect-ratio: 16/9;
    overflow: hidden;
}
.ds-project-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 300ms ease-out;
}
.ds-project-card:hover .ds-project-img {
    transform: scale(1.03);
}
.ds-project-placeholder {
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
.ds-badge-featured {
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background-color: var(--color-secondary);
    color: #fff;
    padding: 4px 10px;
    border-radius: 6px;
    box-shadow: var(--shadow-soft);
}
.ds-card-body {
    padding: 24px;
}
.ds-category-badge {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    color: var(--text-primary);
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 500;
    display: inline-block;
}
.ds-project-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.15rem;
    color: var(--text-primary);
}
.ds-project-author {
    font-size: 0.85rem;
    color: var(--text-secondary);
}
.ds-author-link {
    text-decoration: none;
    color: var(--text-primary);
    font-weight: 500;
    transition: color 150ms;
}
.ds-author-link:hover {
    color: var(--color-primary);
}
.ds-card-footer-line {
    border-top: 1px solid var(--border-soft);
}
.ds-likes {
    font-size: 0.8rem;
    color: #e57373;
    font-weight: 500;
}

/* ── Empty State ── */
.ds-empty-state-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    color: var(--text-secondary);
}
.ds-empty-icon {
    font-size: 3rem;
    opacity: 0.3;
}
.ds-empty-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.15rem;
    color: var(--text-primary);
    margin: 0 0 6px 0;
}
.ds-empty-text {
    font-size: 0.9rem;
    margin: 0;
}

/* ── Buttons ── */
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
