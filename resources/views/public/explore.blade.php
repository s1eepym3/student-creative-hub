@extends('layouts.app')

@section('content')
<div class="ds-explore container py-5">
    
    {{-- Header --}}
    <div class="row g-4 mb-4 align-items-center">
        <div class="col-md-4">
            <h2 class="ds-page-title">Eksplorasi Karya</h2>
            <p class="ds-page-subtitle">Temukan berbagai inovasi dan portofolio terbaik mahasiswa.</p>
        </div>
        <div class="col-md-8">
            <form action="{{ route('public.explore') }}" method="GET" class="ds-search-form d-flex flex-wrap gap-2 justify-content-md-end">
                <div class="ds-search-input-wrapper">
                    <i class="bi bi-search ds-search-icon"></i>
                    <input type="text" name="q" class="ds-input ds-search-field" placeholder="Cari judul, mahasiswa, teknologi..." value="{{ request('q') }}">
                </div>
                
                <select name="category" class="ds-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>

                <select name="skill" class="ds-select">
                    <option value="">Semua Keahlian (Skill)</option>
                    @foreach($skills as $skill)
                        <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>{{ $skill->nama_skill }}</option>
                    @endforeach
                </select>

                <select name="sort" class="ds-select">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="most_liked" {{ request('sort') == 'most_liked' ? 'selected' : '' }}>Paling Disukai</option>
                </select>

                <button type="submit" class="ds-btn-primary"><i class="bi bi-search me-1"></i> Cari</button>
            </form>
        </div>
    </div>

    {{-- Empty State --}}
    @if($projects->isEmpty())
        <div class="ds-empty-state-container text-center py-5 mt-4">
            <div class="ds-empty-icon-circle mb-3">
                <i class="bi bi-search text-muted"></i>
            </div>
            <h4 class="ds-empty-title">Tidak ada karya yang ditemukan</h4>
            <p class="ds-empty-desc mb-4">Coba sesuaikan filter kata kunci, kategori, atau keahlian pencarian Anda.</p>
            @if(request()->anyFilled(['q', 'category', 'skill', 'sort']))
                <a href="{{ route('public.explore') }}" class="ds-btn-primary">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Pencarian
                </a>
            @endif
        </div>
    @else
        {{-- Projects Grid --}}
        <div class="row g-4">
            @foreach($projects as $project)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="ds-project-card d-flex flex-column h-100">
                        <div class="ds-img-container">
                            @if($project->thumbnail)
                                <img src="{{ asset('storage/' . $project->thumbnail) }}" class="ds-project-img" alt="Thumbnail" loading="lazy">
                            @else
                                <div class="ds-project-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                            <span class="ds-category-badge">{{ $project->category->nama_kategori }}</span>
                        </div>
                        <div class="ds-card-body d-flex flex-column flex-grow-1">
                            <h6 class="ds-project-title text-truncate" title="{{ $project->judul }}">
                                <a href="{{ route('public.project_detail', $project->slug) }}" class="text-decoration-none text-dark">
                                    {{ $project->judul }}
                                </a>
                            </h6>
                            <p class="ds-project-author mb-3">
                                Oleh: <a href="{{ route('public.profile', $project->mahasiswa->slug) }}" class="ds-author-link">{{ $project->mahasiswa->nama_lengkap }}</a>
                            </p>
                            
                            @if($project->technologies->count() > 0)
                                <div class="ds-tech-tags mb-3">
                                    @foreach($project->technologies->take(2) as $tech)
                                        <span class="ds-tech-tag">{{ $tech->technology_name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 ds-card-footer-line">
                                <span class="ds-likes"><i class="bi bi-heart-fill me-1"></i> {{ $project->likes_count }}</span>
                                <a href="{{ route('public.project_detail', $project->slug) }}" class="ds-btn-ghost ds-btn-sm">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-5">
            {{ $projects->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
/* ── Layout & Typography ── */
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

/* ── Search Form Styles ── */
.ds-search-form {
    align-items: center;
}
.ds-search-input-wrapper {
    position: relative;
    flex-grow: 1;
    min-width: 240px;
}
.ds-search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    opacity: 0.7;
    font-size: 0.95rem;
}
.ds-search-field {
    padding-left: 38px !important;
}

/* ── Custom Inputs ── */
.ds-input, .ds-select {
    width: 100%;
    border-radius: var(--radius-input);
    border: 1px solid var(--border-soft);
    background-color: var(--surface-primary);
    color: var(--text-primary);
    padding: 10px 14px;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 150ms ease-out, box-shadow 150ms ease-out;
}
.ds-input:focus, .ds-select:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(92, 124, 111, 0.15);
}
.ds-select {
    width: auto;
    min-width: 150px;
    cursor: pointer;
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
.ds-img-container {
    position: relative;
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
.ds-category-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background-color: rgba(252, 250, 248, 0.9);
    border: 1px solid var(--border-soft);
    color: var(--text-primary);
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 500;
}
.ds-card-body {
    padding: 20px;
}
.ds-project-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1rem;
    margin: 0 0 6px 0;
}
.ds-project-author {
    font-size: 0.82rem;
    color: var(--text-secondary);
}
.ds-author-link {
    text-decoration: none;
    color: var(--text-primary);
    font-weight: 500;
}
.ds-author-link:hover {
    color: var(--color-primary);
}
.ds-tech-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.ds-tech-tag {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    color: var(--text-secondary);
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 500;
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
.ds-empty-state-container {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    padding: 48px 24px;
}
.ds-empty-icon-circle {
    width: 64px;
    height: 64px;
    background-color: var(--surface-secondary);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
}
.ds-empty-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.15rem;
    color: var(--text-primary);
}
.ds-empty-desc {
    font-size: 0.875rem;
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

@media (max-width: 767px) {
    .ds-select {
        width: 100%;
    }
}
</style>
@endpush

@endsection
