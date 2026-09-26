@extends('layouts.app')

@section('content')
<div class="ds-dashboard">

    {{-- ── Flash Messages ── --}}
    @if(session('error'))
        <div class="container" style="padding-top: 24px;">
            <div class="ds-alert ds-alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         SECTION 1 — PROFILE OVERVIEW
    ══════════════════════════════════════════════════════ --}}
    <div class="container ds-section-top">

        {{-- Page Title --}}
        <div class="ds-page-header">
            <div>
                <h2 class="ds-page-title">Workspace Saya</h2>
                <p class="ds-page-subtitle">Kelola portofolio dan profil kreatif Anda.</p>
            </div>
        </div>

        {{-- Profile Card --}}
        <div class="ds-card ds-profile-card mb-4">
            <div class="row align-items-center g-4">

                {{-- Avatar --}}
                <div class="col-auto">
                    @if($mahasiswa->foto_profil)
                        <img src="{{ Storage::url($mahasiswa->foto_profil) }}" alt="Profile"
                             class="ds-avatar" loading="lazy">
                    @else
                        <div class="ds-avatar-placeholder">
                            {{ strtoupper(substr($mahasiswa->nama_lengkap, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Identity --}}
                <div class="col">
                    <h4 class="ds-name mb-1">Halo, {{ $user->name }}! 👋</h4>
                    <p class="ds-meta mb-2">
                        <span>{{ $mahasiswa->nim }}</span>
                        <span class="ds-meta-dot">·</span>
                        <span>{{ $mahasiswa->prodi ?? 'Prodi Belum Diatur' }}</span>
                    </p>
                    <a href="{{ route('public.profile', $mahasiswa->slug) }}" target="_blank"
                       class="ds-btn-ghost ds-btn-sm">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Lihat Profil Publik
                    </a>
                </div>

                {{-- Profile Completion --}}
                <div class="col-md-4">
                    <div class="ds-completion-wrap">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="ds-label-sm">Profile Completion</span>
                            <span class="ds-completion-pct" style="color: var(--color-primary);">{{ $profileCompletion }}%</span>
                        </div>
                        <div class="ds-progress">
                            <div class="ds-progress-bar" role="progressbar"
                                 style="width: {{ $profileCompletion }}%;"
                                 aria-valuenow="{{ $profileCompletion }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        @if($profileCompletion < 100)
                            <p class="ds-hint mt-2">Lengkapi data diri dan keahlian Anda untuk mencapai 100%.</p>
                        @else
                            <p class="ds-hint ds-hint-success mt-2">
                                <i class="bi bi-check-circle-fill me-1"></i>Profil Anda sudah lengkap!
                            </p>
                        @endif
                        <a href="{{ route('mahasiswa.profile.edit') }}" class="ds-btn-primary ds-btn-sm w-100 mt-2 text-center d-block">
                            Lengkapi Profil
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Stats Row ── --}}
        <div class="row g-3 mb-4">
            @php
                $stats = [
                    ['label' => 'Projects',     'value' => $totalProjects,     'icon' => 'bi-folder2',          'route' => route('mahasiswa.projects.index')],
                    ['label' => 'Skills',       'value' => $totalSkills,       'icon' => 'bi-lightning-charge', 'route' => route('mahasiswa.skills.index')],
                    ['label' => 'Certificates', 'value' => $totalCertificates, 'icon' => 'bi-patch-check',      'route' => route('mahasiswa.certificates.index')],
                    ['label' => 'Achievements', 'value' => $totalAchievements, 'icon' => 'bi-trophy',           'route' => route('mahasiswa.achievements.index')],
                ];
            @endphp
            @foreach($stats as $stat)
            <div class="col-6 col-md-3">
                <a href="{{ $stat['route'] }}" class="text-decoration-none">
                    <div class="ds-stat-card">
                        <div class="ds-stat-icon">
                            <i class="bi {{ $stat['icon'] }}"></i>
                        </div>
                        <div class="ds-stat-value">{{ $stat['value'] }}</div>
                        <div class="ds-stat-label">{{ $stat['label'] }}</div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        {{-- ── Content Row: QR · PDF · Latest Projects ── --}}
        <div class="row g-4 mb-4">

            {{-- QR Portfolio --}}
            <div class="col-md-4">
                <div class="ds-card h-100 d-flex flex-column">
                    <div class="ds-card-label">
                        <i class="bi bi-qr-code-scan me-2" style="color: var(--color-primary);"></i>QR Portfolio
                    </div>
                    <div class="ds-card-body flex-grow-1 d-flex flex-column align-items-center justify-content-center text-center">
                        @if($mahasiswa->qrPortfolio)
                            <img src="{{ asset('storage/' . $mahasiswa->qrPortfolio->qr_path) }}"
                                 alt="QR Code" class="ds-qr-img mb-4" loading="lazy">
                            <form action="{{ route('mahasiswa.qr.generate') }}" method="POST" class="w-100 mt-auto">
                                @csrf
                                <button class="ds-btn-ghost w-100">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Regenerate QR
                                </button>
                            </form>
                        @else
                            <div class="ds-empty-sm">
                                <i class="bi bi-qr-code ds-empty-icon"></i>
                                <p class="ds-empty-text">Buat QR Code untuk profil publik Anda.</p>
                            </div>
                            <form action="{{ route('mahasiswa.qr.generate') }}" method="POST" class="w-100 mt-auto">
                                @csrf
                                <button class="ds-btn-primary w-100">
                                    <i class="bi bi-plus-lg me-1"></i>Generate QR Code
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Portfolio PDF --}}
            <div class="col-md-4">
                <div class="ds-card h-100 d-flex flex-column">
                    <div class="ds-card-label">
                        <i class="bi bi-file-earmark-pdf me-2" style="color: var(--color-primary);"></i>Portfolio PDF
                    </div>
                    <div class="ds-card-body flex-grow-1 d-flex flex-column align-items-center justify-content-center text-center">
                        <div class="ds-empty-sm">
                            <i class="bi bi-file-earmark-pdf ds-empty-icon"></i>
                            <p class="ds-empty-text">Unduh atau preview portofolio siap cetak untuk magang, beasiswa, dan lamaran kerja.</p>
                        </div>
                        <div class="w-100 d-grid gap-2 mt-auto">
                            <a href="{{ route('mahasiswa.portfolio.pdf') }}" class="ds-btn-primary">
                                <i class="bi bi-download me-1"></i>Download PDF
                            </a>
                            <a href="{{ route('mahasiswa.portfolio.pdf_preview') }}" target="_blank" class="ds-btn-ghost">
                                <i class="bi bi-eye me-1"></i>Preview PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Latest Projects --}}
            <div class="col-md-8">
                <div class="ds-card h-100 d-flex flex-column">
                    <div class="ds-card-label d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-folder-check me-2" style="color: var(--color-primary);"></i>Proyek Terbaru</span>
                        <a href="{{ route('mahasiswa.projects.create') }}" class="ds-btn-primary ds-btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Tambah
                        </a>
                    </div>
                    <div class="ds-card-body p-0 flex-grow-1">
                        @php $latestProjects = $mahasiswa->projects()->latest()->take(4)->get(); @endphp

                        @if($latestProjects->count() > 0)
                            <div class="ds-project-list">
                                @foreach($latestProjects as $project)
                                @php
                                    $statusMap = [
                                        'draft'    => ['label' => 'Draft',    'color' => '#6B6B6B', 'bg' => '#F0EDEA'],
                                        'pending'  => ['label' => 'Pending',  'color' => '#856404', 'bg' => '#FFF3CD'],
                                        'approved' => ['label' => 'Approved', 'color' => '#2E7D32', 'bg' => '#E8F5E9'],
                                        'rejected' => ['label' => 'Rejected', 'color' => '#721C24', 'bg' => '#F8D7DA'],
                                    ];
                                    $s = $statusMap[$project->status] ?? $statusMap['draft'];
                                @endphp
                                <div class="ds-project-row">
                                    <img src="{{ asset('storage/' . $project->thumbnail) }}"
                                         alt="{{ $project->judul }}" class="ds-project-thumb" loading="lazy">
                                    <div class="ds-project-info">
                                        <div class="ds-project-title">{{ Str::limit($project->judul, 40) }}</div>
                                        <div class="ds-project-meta">
                                            <span><i class="bi bi-clock me-1"></i>{{ $project->created_at->diffForHumans() }}</span>
                                            <span class="ds-badge"
                                                  style="color:{{ $s['color'] }};background:{{ $s['bg'] }};">
                                                {{ $s['label'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ds-project-actions">
                                        @if(in_array($project->status, ['draft', 'rejected']))
                                            <a href="{{ route('mahasiswa.projects.edit', $project) }}"
                                               class="ds-icon-btn" title="Edit Project">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif
                                        @if($project->status === 'approved')
                                            @if(!$project->revisions()->where('status', 'pending')->exists())
                                                <a href="{{ route('mahasiswa.projects.revision.create', $project) }}"
                                                   class="ds-icon-btn ds-icon-btn-warn" title="Request Revision">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endif
                                            <button type="button" class="ds-icon-btn ds-icon-btn-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteRequestModal{{ $project->id }}"
                                                    title="Request Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                            {{-- Delete Request Modal --}}
                                            <div class="modal fade" id="deleteRequestModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <form action="{{ route('mahasiswa.projects.request_delete', $project) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-content ds-modal">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Ajukan Penghapusan Proyek</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="ds-hint mb-3">Proyek yang sudah disetujui tidak bisa langsung dihapus. Anda harus meminta persetujuan Admin.</p>
                                                                <div class="mb-3">
                                                                    <label for="deletion_reason_{{ $project->id }}" class="ds-form-label">
                                                                        Alasan Penghapusan <span style="color: #e57373;">*</span>
                                                                    </label>
                                                                    <textarea class="ds-textarea" id="deletion_reason_{{ $project->id }}" name="deletion_reason"
                                                                              rows="4" required minlength="10"
                                                                              placeholder="Mengapa proyek ini perlu dihapus?"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="ds-btn-ghost" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="ds-btn-danger">Ajukan Penghapusan</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="ds-view-all">
                                <a href="{{ route('mahasiswa.projects.index') }}" class="ds-view-all-link">
                                    Semua Proyek <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="ds-empty-state">
                                <i class="bi bi-folder2-open ds-empty-icon"></i>
                                <p class="ds-empty-title">Belum Ada Proyek</p>
                                <p class="ds-empty-text">Mulai bangun portofolio kreatif Anda dengan mengunggah proyek pertama.</p>
                                <a href="{{ route('mahasiswa.projects.create') }}" class="ds-btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i>Buat Proyek Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>{{-- /container section 1 --}}

    {{-- ══════════════════════════════════════════════════════
         SECTION 2 — PORTFOLIO ANALYTICS
    ══════════════════════════════════════════════════════ --}}
    <div class="ds-section-analytics">
        <div class="container">
            <div class="ds-section-divider"></div>

            <div class="ds-section-header mb-4">
                <div>
                    <h3 class="ds-section-title">
                        <i class="bi bi-graph-up-arrow me-2" style="color: var(--color-primary);"></i>Statistik &amp; Analisis Portofolio
                    </h3>
                    <p class="ds-page-subtitle">Pantau performa kunjungan portofolio, proyek, dan rekomendasi optimalisasi.</p>
                </div>
            </div>

            {{-- Insights --}}
            @if(count($insights) > 0)
            <div class="ds-card mb-4">
                <div class="ds-card-label">
                    <i class="bi bi-lightbulb me-2" style="color: var(--color-secondary);"></i>Rekomendasi Portofolio Anda
                </div>
                <div class="ds-card-body pt-0">
                    <div class="row g-3">
                        @foreach($insights as $insight)
                        @php
                            $insightStyles = [
                                'warning' => ['bg' => '#FFF8F0', 'border' => '#C48A5A', 'icon' => 'bi-exclamation-triangle-fill', 'color' => '#856404'],
                                'success' => ['bg' => '#F0F7F4', 'border' => '#5C7C6F', 'icon' => 'bi-check-circle-fill',         'color' => '#2E7D32'],
                                'info'    => ['bg' => '#F0F5FF', 'border' => '#6B8EC4', 'icon' => 'bi-info-circle-fill',           'color' => '#1a56a0'],
                            ];
                            $is = $insightStyles[$insight['type']] ?? $insightStyles['info'];
                        @endphp
                        <div class="col-md-6">
                            <div class="ds-insight"
                                 style="background:{{ $is['bg'] }};border-left: 3px solid {{ $is['border'] }};">
                                <div class="ds-insight-icon">
                                    <i class="bi {{ $is['icon'] }}" style="color:{{ $is['color'] }};"></i>
                                </div>
                                <div>
                                    <div class="ds-insight-title">{{ $insight['title'] }}</div>
                                    <p class="ds-insight-text">{{ $insight['text'] }}</p>
                                    <small class="ds-insight-impact">
                                        <i class="bi bi-lightning-charge me-1" style="color: var(--color-primary);"></i>Dampak: {{ $insight['improvement'] }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Analytics Stats --}}
            <div class="row g-3 mb-4">

                {{-- Portfolio Views --}}
                <div class="col-md-4">
                    <div class="ds-card ds-analytics-card h-100">
                        <div class="ds-analytics-header">
                            <span class="ds-analytics-label">Kunjungan Profil</span>
                            <i class="bi bi-eye ds-analytics-icon"></i>
                        </div>
                        <div class="ds-analytics-value">{{ $totalPortfolioViews }}</div>
                        <div class="ds-analytics-sub">Total Views</div>
                        <div class="ds-analytics-breakdown">
                            @foreach(['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $key => $lbl)
                            <div class="ds-breakdown-item">
                                <div class="ds-breakdown-label">{{ $lbl }}</div>
                                <div class="ds-breakdown-count">{{ $portfolioGrowth[$key]['count'] }}</div>
                                @if($portfolioGrowth[$key]['growth']['direction'] !== 'none')
                                <div class="ds-breakdown-growth ds-growth-{{ $portfolioGrowth[$key]['growth']['direction'] }}">
                                    {{ $portfolioGrowth[$key]['growth']['text'] }}
                                </div>
                                @else
                                <div class="ds-breakdown-growth" style="color: var(--text-secondary);">{{ $portfolioGrowth[$key]['growth']['text'] }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Project Views --}}
                <div class="col-md-4">
                    <div class="ds-card ds-analytics-card h-100">
                        <div class="ds-analytics-header">
                            <span class="ds-analytics-label">Kunjungan Proyek</span>
                            <i class="bi bi-folder2-open ds-analytics-icon"></i>
                        </div>
                        <div class="ds-analytics-value">{{ $totalProjectViews }}</div>
                        <div class="ds-analytics-sub">Total Views</div>
                        <div class="ds-analytics-breakdown">
                            @foreach(['today' => 'Hari Ini', 'week' => 'Minggu Ini', 'month' => 'Bulan Ini'] as $key => $lbl)
                            <div class="ds-breakdown-item">
                                <div class="ds-breakdown-label">{{ $lbl }}</div>
                                <div class="ds-breakdown-count">{{ $projectGrowth[$key]['count'] }}</div>
                                @if($projectGrowth[$key]['growth']['direction'] !== 'none')
                                <div class="ds-breakdown-growth ds-growth-{{ $projectGrowth[$key]['growth']['direction'] }}">
                                    {{ $projectGrowth[$key]['growth']['text'] }}
                                </div>
                                @else
                                <div class="ds-breakdown-growth" style="color: var(--text-secondary);">{{ $projectGrowth[$key]['growth']['text'] }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- QR & Most Viewed --}}
                <div class="col-md-4">
                    <div class="ds-card ds-analytics-card h-100">
                        <div class="ds-analytics-header">
                            <span class="ds-analytics-label">Scan QR &amp; Terpopuler</span>
                            <i class="bi bi-qr-code-scan ds-analytics-icon"></i>
                        </div>
                        <div class="ds-analytics-misc">
                            <div class="ds-misc-row">
                                <span><i class="bi bi-qr-code me-1"></i>Total Scan QR</span>
                                <strong>{{ $totalQrScans }}</strong>
                            </div>
                            <div class="ds-misc-row">
                                <span><i class="bi bi-eye me-1"></i>Paling Dilihat</span>
                                <strong class="text-truncate ms-2" style="max-width:120px;color:var(--color-primary);"
                                        title="{{ $mostViewedProject?->judul ?? '-' }}">
                                    {{ $mostViewedProject?->judul ?? '-' }}
                                </strong>
                            </div>
                            <div class="ds-misc-row">
                                <span><i class="bi bi-heart me-1"></i>Paling Disukai</span>
                                <strong class="text-truncate ms-2" style="max-width:120px;color:var(--color-secondary);"
                                        title="{{ $mostLikedProject?->judul ?? '-' }}">
                                    {{ $mostLikedProject?->judul ?? '-' }}
                                </strong>
                            </div>
                        </div>
                        <div class="ds-analytics-footer">
                            <i class="bi bi-info-circle me-1"></i>Diperbarui otomatis setiap 5 menit.
                        </div>
                    </div>
                </div>

            </div>{{-- /analytics stats row --}}

            {{-- Charts Row --}}
            <div class="row g-4 mb-4">

                {{-- Line Chart --}}
                <div class="col-md-7">
                    <div class="ds-card h-100">
                        <div class="ds-card-label">
                            <i class="bi bi-calendar-range me-2" style="color: var(--color-primary);"></i>Tren Pengunjung Bulanan (6 Bulan Terakhir)
                        </div>
                        <div class="ds-card-body">
                            <div style="height: 300px; position: relative;">
                                <canvas id="monthlyTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Doughnut / Bar Chart --}}
                <div class="col-md-5">
                    <div class="ds-card h-100">
                        <div class="ds-card-label d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-pie-chart me-2" style="color: var(--color-primary);"></i>Distribusi Kunjungan Proyek</span>
                            <div class="ds-tab-group">
                                <button class="ds-tab active" id="btnCategoryChart">Kategori</button>
                                <button class="ds-tab" id="btnTechChart">Teknologi</button>
                            </div>
                        </div>
                        <div class="ds-card-body d-flex align-items-center justify-content-center">
                            <div id="categoryChartContainer" class="w-100" style="height:300px;position:relative;">
                                @if(count($categoryDistribution['values']) > 0)
                                    <canvas id="categoryChart"></canvas>
                                @else
                                    <div class="ds-empty-state">
                                        <i class="bi bi-pie-chart ds-empty-icon"></i>
                                        <p class="ds-empty-text">Belum ada data kunjungan kategori proyek.</p>
                                    </div>
                                @endif
                            </div>
                            <div id="techChartContainer" class="w-100 d-none" style="height:300px;position:relative;">
                                @if(count($technologyDistribution['values']) > 0)
                                    <canvas id="techChart"></canvas>
                                @else
                                    <div class="ds-empty-state">
                                        <i class="bi bi-bar-chart ds-empty-icon"></i>
                                        <p class="ds-empty-text">Belum ada data kunjungan teknologi proyek.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /charts row --}}

        </div>{{-- /container section 2 --}}
    </div>{{-- /analytics section --}}

</div>{{-- /ds-dashboard --}}

{{-- ══════════════════════════════════════════════════════
     PAGE-SCOPED STYLES
══════════════════════════════════════════════════════ --}}
@push('styles')
<style>
/* ── Layout ── */
.ds-dashboard         { padding-bottom: 64px; }
.ds-section-top       { padding-top: 40px; }
.ds-section-analytics { padding-top: 16px; }
.ds-section-divider   { border: none; border-top: 1px solid var(--border-soft); margin: 0 0 32px; }

/* ── Page Header ── */
.ds-page-header   { display:flex;justify-content:space-between;align-items:center;margin-bottom:32px; }
.ds-page-title    { font-family:var(--font-heading);font-weight:600;font-size:1.5rem;color:var(--text-primary);margin:0; }
.ds-page-subtitle { font-size:.875rem;color:var(--text-secondary);margin:4px 0 0; }
.ds-section-title { font-family:var(--font-heading);font-weight:600;font-size:1.25rem;color:var(--text-primary);margin:0; }
.ds-section-header{ margin-bottom:24px; }

/* ── Cards ── */
.ds-card        { background:var(--surface-primary);border:1px solid var(--border-soft);
                  border-radius:var(--radius-card);box-shadow:var(--shadow-soft);
                  transition:transform 150ms ease-out,box-shadow 150ms ease-out; }
.ds-card:hover  { transform:scale(1.005);box-shadow:0 6px 20px rgba(0,0,0,.06); }
.ds-card-label  { padding:18px 24px;font-family:var(--font-heading);font-weight:600;
                  font-size:.9rem;color:var(--text-primary);
                  border-bottom:1px solid var(--border-soft); }
.ds-card-body   { padding:20px 24px; }

/* ── Profile Card ── */
.ds-profile-card  { padding:28px 32px; }
.ds-avatar        { width:88px;height:88px;border-radius:50%;object-fit:cover;
                    border:3px solid var(--border-soft); }
.ds-avatar-placeholder { width:88px;height:88px;border-radius:50%;
                          background:var(--surface-secondary);
                          border:3px solid var(--border-soft);
                          display:flex;align-items:center;justify-content:center;
                          font-family:var(--font-heading);font-weight:700;font-size:2rem;
                          color:var(--color-primary); }
.ds-name          { font-family:var(--font-heading);font-weight:600;font-size:1.2rem;color:var(--text-primary); }
.ds-meta          { font-size:.85rem;color:var(--text-secondary); }
.ds-meta-dot      { margin:0 6px;opacity:.4; }
.ds-completion-wrap{ background:var(--surface-secondary);border-radius:16px;padding:16px 20px; }
.ds-label-sm      { font-size:.8rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px; }
.ds-completion-pct{ font-family:var(--font-heading);font-weight:700;font-size:1.2rem; }
.ds-progress      { height:8px;background:var(--bg-section);border-radius:8px;overflow:hidden; }
.ds-progress-bar  { height:100%;background:var(--color-primary);border-radius:8px;
                    transition:width .6s ease-out; }
.ds-hint          { font-size:.8rem;color:var(--text-secondary);margin:0; }
.ds-hint-success  { color:var(--color-primary); }

/* ── Stat Cards ── */
.ds-stat-card  { background:var(--surface-primary);border:1px solid var(--border-soft);
                 border-radius:var(--radius-card);box-shadow:var(--shadow-soft);
                 padding:24px 20px;text-align:center;
                 transition:transform 150ms ease-out,background 150ms ease-out; }
.ds-stat-card:hover { background:var(--surface-hover);transform:translateY(-2px); }
.ds-stat-icon  { width:48px;height:48px;border-radius:14px;margin:0 auto 12px;
                 display:flex;align-items:center;justify-content:center;
                 background:var(--surface-secondary);font-size:1.3rem;color:var(--color-primary); }
.ds-stat-value { font-family:var(--font-heading);font-size:1.75rem;font-weight:700;
                 color:var(--text-primary);line-height:1; }
.ds-stat-label { font-size:.8rem;color:var(--text-secondary);margin-top:4px;text-transform:uppercase;letter-spacing:.5px; }

/* ── QR Image ── */
.ds-qr-img { max-height:160px;border-radius:var(--radius-image);
             border:1px solid var(--border-soft);padding:8px;background:var(--surface-secondary); }

/* ── Project List ── */
.ds-project-list { }
.ds-project-row  { display:flex;align-items:center;gap:14px;padding:14px 20px;
                   border-bottom:1px solid var(--border-soft);
                   transition:background 150ms ease-out; }
.ds-project-row:last-child { border-bottom:none; }
.ds-project-row:hover { background:var(--surface-hover); }
.ds-project-thumb{ width:48px;height:48px;border-radius:10px;object-fit:cover;flex-shrink:0; }
.ds-project-info { flex:1;min-width:0; }
.ds-project-title{ font-weight:600;font-size:.9rem;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.ds-project-meta { display:flex;align-items:center;gap:8px;margin-top:3px;font-size:.78rem;color:var(--text-secondary); }
.ds-project-actions{ display:flex;gap:6px;flex-shrink:0; }
.ds-badge        { padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:600; }
.ds-view-all     { padding:12px 20px;border-top:1px solid var(--border-soft);text-align:center; }
.ds-view-all-link{ font-size:.85rem;font-weight:600;color:var(--color-primary);text-decoration:none; }
.ds-view-all-link:hover{ color:var(--color-secondary); }

/* ── Empty States ── */
.ds-empty-state { display:flex;flex-direction:column;align-items:center;justify-content:center;
                  padding:48px 24px;text-align:center; }
.ds-empty-sm    { display:flex;flex-direction:column;align-items:center;text-align:center;margin-bottom:16px; }
.ds-empty-icon  { font-size:2.5rem;color:var(--border-soft);margin-bottom:12px; }
.ds-empty-title { font-family:var(--font-heading);font-weight:600;font-size:1rem;color:var(--text-primary);margin:0 0 6px; }
.ds-empty-text  { font-size:.85rem;color:var(--text-secondary);margin:0 0 20px;line-height:1.5; }

/* ── Buttons ── */
.ds-btn-primary { display:inline-flex;align-items:center;justify-content:center;
                  background:var(--color-primary);color:#fff;border:none;
                  border-radius:var(--radius-button);padding:10px 20px;font-size:.875rem;
                  font-weight:500;cursor:pointer;text-decoration:none;
                  transition:background 150ms ease-out,transform 150ms ease-out; }
.ds-btn-primary:hover{ background:#4a6459;transform:translateY(-1px);color:#fff; }
.ds-btn-ghost   { display:inline-flex;align-items:center;justify-content:center;
                  background:transparent;color:var(--text-primary);
                  border:1px solid var(--border-soft);border-radius:var(--radius-button);
                  padding:10px 20px;font-size:.875rem;font-weight:500;cursor:pointer;
                  text-decoration:none;transition:all 150ms ease-out; }
.ds-btn-ghost:hover{ background:var(--surface-hover);border-color:var(--color-primary);color:var(--color-primary); }
.ds-btn-danger  { display:inline-flex;align-items:center;justify-content:center;
                  background:#e57373;color:#fff;border:none;
                  border-radius:var(--radius-button);padding:10px 20px;
                  font-size:.875rem;font-weight:500;cursor:pointer;text-decoration:none;
                  transition:background 150ms ease-out; }
.ds-btn-danger:hover{ background:#d32f2f; }
.ds-btn-sm      { padding:6px 14px !important;font-size:.8rem !important; }

/* ── Icon Buttons ── */
.ds-icon-btn       { width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;
                     background:var(--surface-secondary);border:1px solid var(--border-soft);
                     border-radius:10px;color:var(--text-secondary);font-size:.9rem;
                     text-decoration:none;transition:all 150ms ease-out;cursor:pointer; }
.ds-icon-btn:hover       { background:var(--surface-hover);color:var(--color-primary);border-color:var(--color-primary); }
.ds-icon-btn-warn:hover  { background:#FFF8F0;color:#856404;border-color:#C48A5A; }
.ds-icon-btn-danger:hover{ background:#fdf0f0;color:#e57373;border-color:#e57373; }

/* ── Alerts ── */
.ds-alert         { padding:14px 20px;border-radius:var(--radius-button);font-size:.875rem; }
.ds-alert-danger  { background:#FDF0F0;color:#721C24;border:1px solid rgba(229,115,115,.3); }

/* ── Modal ── */
.ds-modal         { background:var(--surface-modal) !important;border-radius:var(--radius-modal) !important; }
.ds-form-label    { font-size:.875rem;font-weight:500;color:var(--text-primary);margin-bottom:6px;display:block; }
.ds-textarea      { width:100%;padding:12px 16px;border:1px solid var(--border-soft);
                    border-radius:var(--radius-input);background:var(--surface-primary);
                    font-family:var(--font-body);font-size:.875rem;color:var(--text-primary);
                    resize:vertical;transition:border-color 150ms; }
.ds-textarea:focus{ outline:none;border-color:var(--color-primary);
                    box-shadow:0 0 0 3px rgba(92,124,111,.12); }

/* ── Insights ── */
.ds-insight       { display:flex;gap:12px;padding:16px;border-radius:14px;height:100%; }
.ds-insight-icon  { flex-shrink:0;font-size:1.2rem;padding-top:2px; }
.ds-insight-title { font-family:var(--font-heading);font-weight:600;font-size:.9rem;
                    color:var(--text-primary);margin-bottom:4px; }
.ds-insight-text  { font-size:.83rem;color:var(--text-primary);margin:0 0 6px;line-height:1.5; }
.ds-insight-impact{ font-size:.75rem;font-weight:600;color:var(--text-secondary); }

/* ── Analytics Cards ── */
.ds-analytics-card     { padding:24px; }
.ds-analytics-header   { display:flex;justify-content:space-between;align-items:center;margin-bottom:16px; }
.ds-analytics-label    { font-size:.75rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.6px; }
.ds-analytics-icon     { font-size:1.4rem;color:var(--border-soft); }
.ds-analytics-value    { font-family:var(--font-heading);font-size:2.5rem;font-weight:700;color:var(--text-primary);line-height:1; }
.ds-analytics-sub      { font-size:.8rem;color:var(--text-secondary);margin:4px 0 16px; }
.ds-analytics-breakdown{ display:flex;gap:0;border-top:1px solid var(--border-soft);padding-top:16px; }
.ds-breakdown-item     { flex:1;text-align:center;padding:0 8px;border-right:1px solid var(--border-soft); }
.ds-breakdown-item:last-child{ border-right:none; }
.ds-breakdown-label    { font-size:.7rem;color:var(--text-secondary);margin-bottom:4px; }
.ds-breakdown-count    { font-family:var(--font-heading);font-weight:600;font-size:.95rem;color:var(--text-primary); }
.ds-breakdown-growth   { font-size:.7rem;font-weight:600;margin-top:2px; }
.ds-growth-up          { color:#2E7D32; }
.ds-growth-down        { color:#e57373; }
.ds-analytics-misc     { display:flex;flex-direction:column;gap:12px;margin-bottom:16px; }
.ds-misc-row           { display:flex;justify-content:space-between;align-items:center;
                         font-size:.85rem;color:var(--text-secondary); }
.ds-analytics-footer   { font-size:.73rem;color:var(--text-secondary);border-top:1px solid var(--border-soft);
                         padding-top:12px;text-align:center; }

/* ── Tabs ── */
.ds-tab-group  { display:flex;border:1px solid var(--border-soft);border-radius:10px;overflow:hidden;background:var(--surface-secondary); }
.ds-tab        { padding:5px 14px;font-size:.8rem;font-weight:500;border:none;background:transparent;
                 color:var(--text-secondary);cursor:pointer;transition:all 150ms; }
.ds-tab.active { background:var(--color-primary);color:#fff; }
.ds-tab:hover:not(.active){ background:var(--surface-hover);color:var(--text-primary); }
</style>
@endpush

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const PRIMARY   = '#5C7C6F';
    const SECONDARY = '#C48A5A';
    const WARN      = '#C48A5A';

    // 1. Monthly Visitor Trend Line Chart
    const trendCtx = document.getElementById('monthlyTrendChart');
    if (trendCtx) {
        new Chart(trendCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($monthlyTrend['labels']),
                datasets: [
                    {
                        label: 'Profil (Portfolio)',
                        data: @json($monthlyTrend['portfolio']),
                        borderColor: PRIMARY,
                        backgroundColor: 'rgba(92,124,111,.07)',
                        fill: true, tension: 0.4, borderWidth: 2, pointRadius: 3
                    },
                    {
                        label: 'Proyek (Projects)',
                        data: @json($monthlyTrend['project']),
                        borderColor: SECONDARY,
                        backgroundColor: 'rgba(196,138,90,.07)',
                        fill: true, tension: 0.4, borderWidth: 2, pointRadius: 3
                    },
                    {
                        label: 'Scan Kode QR',
                        data: @json($monthlyTrend['qr']),
                        borderColor: '#A8B8B0',
                        backgroundColor: 'rgba(168,184,176,.07)',
                        fill: true, tension: 0.4, borderWidth: 2, pointRadius: 3
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14, font: { size: 11, family: 'Inter' } } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0, font: { size: 11 } }, grid: { color: 'rgba(216,207,196,.3)' } },
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                }
            }
        });
    }

    // 2. Category Distribution
    const categoryDataValues = @json($categoryDistribution['values']);
    if (categoryDataValues.length > 0) {
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            new Chart(categoryCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($categoryDistribution['labels']),
                    datasets: [{ data: categoryDataValues,
                        backgroundColor: ['#5C7C6F','#C48A5A','#A8B8B0','#8BA399','#D4C4B0','#B8A898','#7A9E94','#E8C4A0','#6B8E7F','#C4A882']
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '65%',
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 10, font: { size: 10 } } } }
                }
            });
        }
    }

    // 3. Technology Distribution
    const techDataValues = @json($technologyDistribution['values']);
    if (techDataValues.length > 0) {
        const techCtx = document.getElementById('techChart');
        if (techCtx) {
            new Chart(techCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($technologyDistribution['labels']),
                    datasets: [{ label: 'Jumlah Kunjungan', data: techDataValues,
                        backgroundColor: PRIMARY, borderRadius: 6 }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0, font: { size: 11 } }, grid: { color: 'rgba(216,207,196,.3)' } },
                        x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                    }
                }
            });
        }
    }

    // 4. Tab Switching
    const btnCategory = document.getElementById('btnCategoryChart');
    const btnTech     = document.getElementById('btnTechChart');
    const contCat     = document.getElementById('categoryChartContainer');
    const contTech    = document.getElementById('techChartContainer');
    if (btnCategory && btnTech && contCat && contTech) {
        btnCategory.addEventListener('click', function () {
            btnCategory.classList.add('active'); btnTech.classList.remove('active');
            contCat.classList.remove('d-none'); contTech.classList.add('d-none');
        });
        btnTech.addEventListener('click', function () {
            btnTech.classList.add('active'); btnCategory.classList.remove('active');
            contTech.classList.remove('d-none'); contCat.classList.add('d-none');
        });
    }
});
</script>
@endpush