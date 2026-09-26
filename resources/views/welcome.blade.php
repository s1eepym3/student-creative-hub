@extends('layouts.app')

@section('content')
<div class="ds-welcome">

    {{-- ====== HERO SECTION ====== --}}
    <div class="container ds-hero-section">
        <div class="row align-items-center mb-5 g-5">
            {{-- Left: CTA Text --}}
            <div class="col-lg-6">
                <span class="ds-badge-top mb-3">Malikussaleh Student Creative Platform</span>
                <h1 class="ds-hero-title mb-3">Student Creative Hub</h1>
                <p class="ds-hero-lead mb-4">
                    Wadah bagi mahasiswa Universitas Malikussaleh untuk menyimpan portofolio,
                    memamerkan karya terbaik, dan mendokumentasikan setiap prestasi secara profesional.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @guest
                        <a href="{{ route('register') }}" class="ds-btn-primary px-4">Bergabung Sekarang</a>
                        <a href="{{ route('login') }}" class="ds-btn-ghost px-4">Masuk</a>
                    @else
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="ds-btn-primary px-4">Ke Dashboard Admin</a>
                        @else
                            <a href="{{ route('mahasiswa.dashboard') }}" class="ds-btn-primary px-4">Ke Dashboard Saya</a>
                        @endif
                    @endguest
                    <a href="{{ route('public.explore') }}" class="ds-btn-ghost px-4">Jelajahi Karya</a>
                </div>
            </div>

            {{-- Right: Hero Carousel (featured showcases) --}}
            <div class="col-lg-6">
                @if(isset($showcases) && $showcases->count() > 0)
                    <div id="heroCarousel" class="carousel slide ds-carousel" data-bs-ride="carousel" data-bs-interval="3500">
                        <div class="carousel-inner">
                            @foreach($showcases as $index => $showcase)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @if($showcase->project->thumbnail)
                                        <img src="{{ asset('storage/' . $showcase->project->thumbnail) }}"
                                             class="d-block w-100 ds-carousel-img"
                                             alt="{{ $showcase->project->judul }}"
                                             loading="lazy">
                                    @else
                                        <div class="ds-carousel-placeholder">
                                            <i class="bi bi-images"></i>
                                        </div>
                                    @endif
                                    <div class="ds-carousel-caption">
                                        <span class="ds-badge-featured mb-2"><i class="bi bi-star me-1"></i> Featured</span>
                                        <h5 class="fw-bold mb-1" style="color: #fff !important;">{{ $showcase->project->judul }}</h5>
                                        <p class="small mb-0 opacity-75">Oleh: {{ $showcase->project->mahasiswa->nama_lengkap }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($showcases->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                            <div class="carousel-indicators" style="bottom: 0.5rem;">
                                @foreach($showcases as $index => $showcase)
                                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}"
                                            class="{{ $index === 0 ? 'active' : '' }}"
                                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="ds-carousel-placeholder">
                        <div class="text-center">
                            <i class="bi bi-images display-4 text-muted"></i>
                            <h5 class="text-muted mt-3">Karya unggulan akan muncul di sini</h5>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ====== FEATURES SECTION ====== --}}
    <div class="ds-features-wrapper">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="ds-card h-100">
                        <div class="ds-card-body text-center py-4">
                            <div class="ds-feature-icon mb-3"><i class="bi bi-person-vcard text-success"></i></div>
                            <h3 class="ds-feature-title mb-3">Portofolio Digital</h3>
                            <p class="ds-feature-desc mb-0">Kelola profil profesional, tambahkan CV, keahlian, dan tautan jejaring sosial seperti LinkedIn & GitHub.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ds-card h-100">
                        <div class="ds-card-body text-center py-4">
                            <div class="ds-feature-icon mb-3"><i class="bi bi-folder2 text-success"></i></div>
                            <h3 class="ds-feature-title mb-3">Repository Karya</h3>
                            <p class="ds-feature-desc mb-0">Simpan proyek riset dan karya kreatif dengan terstruktur sehingga mudah diakses oleh kurator maupun pihak luar.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ds-card h-100">
                        <div class="ds-card-body text-center py-4">
                            <div class="ds-feature-icon mb-3"><i class="bi bi-award text-success"></i></div>
                            <h3 class="ds-feature-title mb-3">Campus Showcase</h3>
                            <p class="ds-feature-desc mb-0">Proyek-proyek terbaik akan terpilih masuk Showcase Kampus dan berpeluang lebih besar dilirik oleh industri.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== CAMPUS SHOWCASE SECTION ====== --}}
    @if(isset($showcases) && $showcases->count() > 0)
    <div class="container py-5">
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h2 class="ds-section-title mb-1">Campus Showcase</h2>
                <p class="ds-section-subtitle mb-0">Karya terbaik pilihan kurator kampus.</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('public.showcase') }}" class="ds-btn-ghost">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($showcases as $showcase)
                <div class="col">
                    <div class="ds-card ds-project-card h-100 d-flex flex-column">
                        <div class="position-relative">
                            @if($showcase->project->thumbnail)
                                <img src="{{ asset('storage/' . $showcase->project->thumbnail) }}"
                                     class="ds-project-img"
                                     alt="{{ $showcase->project->judul }}"
                                     loading="lazy">
                            @else
                                <div class="ds-project-img-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                            <span class="position-absolute top-0 end-0 m-3 ds-badge-tag bg-warning text-dark">
                                <i class="bi bi-star"></i> Featured
                            </span>
                        </div>
                        <div class="ds-card-body d-flex flex-column flex-grow-1">
                            <h5 class="ds-card-title fw-bold mb-1 text-truncate" title="{{ $showcase->project->judul }}">
                                {{ $showcase->project->judul }}
                            </h5>
                            <p class="ds-card-author mb-3">Oleh: {{ $showcase->project->mahasiswa->nama_lengkap }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="ds-likes-count"><i class="bi bi-heart-fill me-1"></i> {{ $showcase->project->likes_count ?? 0 }} Likes</span>
                                <a href="{{ route('public.project_detail', $showcase->project->slug) }}"
                                   class="ds-btn-primary ds-btn-sm">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('public.explore') }}" class="ds-btn-ghost px-5">
                <i class="bi bi-compass me-2"></i>Eksplorasi Semua Karya
            </a>
        </div>
    </div>
    @endif

</div>

@push('styles')
<style>
/* ── Layout & Sections ── */
.ds-welcome {
    padding-bottom: 64px;
}
.ds-hero-section {
    padding-top: 64px;
    padding-bottom: 48px;
}
.ds-features-wrapper {
    background-color: var(--bg-section);
    border-top: 1px solid var(--border-soft);
    border-bottom: 1px solid var(--border-soft);
    margin: 32px 0;
}

/* ── Typography & Headers ── */
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
    font-size: 3rem;
    line-height: 1.15;
    color: var(--text-primary);
}
.ds-hero-lead {
    font-size: 1.125rem;
    color: var(--text-secondary);
    line-height: 1.6;
}
.ds-section-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.75rem;
    color: var(--text-primary);
}
.ds-section-subtitle {
    font-size: 0.95rem;
    color: var(--text-secondary);
}

/* ── Cards & Visual Enhancements ── */
.ds-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
    transition: transform 150ms ease-out, box-shadow 150ms ease-out;
}
.ds-card:hover {
    transform: scale(1.015);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
}
.ds-card-body {
    padding: 24px;
}

/* ── Feature Cards ── */
.ds-feature-icon {
    width: 64px;
    height: 64px;
    background-color: var(--surface-secondary);
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: var(--color-primary) !important;
}
.ds-feature-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.125rem;
    color: var(--text-primary);
}
.ds-feature-desc {
    font-size: 0.875rem;
    color: var(--text-secondary);
    line-height: 1.5;
}

/* ── Project Cards ── */
.ds-project-card {
    background-color: var(--surface-project) !important;
    overflow: hidden;
}
.ds-project-img {
    width: 100%;
    aspect-ratio: 16/9;
    object-fit: cover;
    border-top-left-radius: var(--radius-card);
    border-top-right-radius: var(--radius-card);
}
.ds-project-img-placeholder {
    width: 100%;
    aspect-ratio: 16/9;
    background-color: var(--surface-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--text-secondary);
    opacity: 0.5;
}
.ds-badge-tag {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
    text-transform: uppercase;
}
.ds-card-title {
    font-family: var(--font-heading);
    font-size: 1.1rem;
    color: var(--text-primary);
}
.ds-card-author {
    font-size: 0.85rem;
    color: var(--text-secondary);
}
.ds-likes-count {
    font-size: 0.8rem;
    color: #e57373;
    font-weight: 500;
}

/* ── Carousel Styling ── */
.ds-carousel {
    border-radius: var(--radius-card);
    border: 1px solid var(--border-soft);
    overflow: hidden;
    box-shadow: var(--shadow-soft);
}
.ds-carousel-img {
    aspect-ratio: 16/9;
    object-fit: cover;
}
.ds-carousel-placeholder {
    background-color: var(--surface-secondary);
    aspect-ratio: 16/9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    border-radius: var(--radius-card);
}
.ds-carousel-caption {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(46, 46, 46, 0.9), rgba(46, 46, 46, 0));
    padding: 32px 24px 20px;
}
.ds-badge-featured {
    display: inline-block;
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background-color: var(--color-secondary);
    color: #fff;
    padding: 3px 8px;
    border-radius: 4px;
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

@media(max-width: 991px) {
    .ds-hero-title {
        font-size: 2.25rem;
    }
}
</style>
@endpush

@endsection
