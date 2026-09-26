@extends('layouts.app')

@section('content')
<div class="ds-admin-dashboard container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="ds-page-title">Admin Dashboard</h2>
                    <p class="ds-page-subtitle">Ringkasan statistik platform dan status proyek mahasiswa.</p>
                </div>
            </div>

            {{-- Stats Row --}}
            <div class="row g-4 mb-5">
                <div class="col-md-3 col-6">
                    <div class="ds-stat-card">
                        <div class="ds-stat-icon" style="background-color: rgba(92, 124, 111, 0.1); color: var(--color-primary);">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ds-stat-content">
                            <span class="ds-stat-label">Total Pengguna</span>
                            <span class="ds-stat-value">{{ $totalUsers }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="ds-stat-card">
                        <div class="ds-stat-icon" style="background-color: rgba(92, 147, 192, 0.1); color: #5c93c0;">
                            <i class="bi bi-folder"></i>
                        </div>
                        <div class="ds-stat-content">
                            <span class="ds-stat-label">Total Proyek</span>
                            <span class="ds-stat-value">{{ $totalProjects }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="ds-stat-card">
                        <div class="ds-stat-icon" style="background-color: rgba(196, 138, 90, 0.1); color: var(--color-secondary);">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div class="ds-stat-content">
                            <span class="ds-stat-label">Menunggu Verifikasi</span>
                            <span class="ds-stat-value">{{ $pendingProjects }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="ds-stat-card">
                        <div class="ds-stat-icon" style="background-color: rgba(92, 158, 108, 0.1); color: #5c9e6c;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="ds-stat-content">
                            <span class="ds-stat-label">Proyek Disetujui</span>
                            <span class="ds-stat-value">{{ $approvedProjects }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Row --}}
            <div class="row g-4 mb-5">
                {{-- Doughnut Chart --}}
                <div class="col-md-5">
                    <div class="ds-card h-100">
                        <div class="ds-card-label">
                            <i class="bi bi-pie-chart me-2" style="color: var(--color-primary);"></i>Distribusi Status Proyek
                        </div>
                        <div class="ds-card-body d-flex align-items-center justify-content-center">
                            <div style="position: relative; height: 260px; width: 100%;">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bar Chart --}}
                <div class="col-md-7">
                    <div class="ds-card h-100">
                        <div class="ds-card-label">
                            <i class="bi bi-bar-chart me-2" style="color: var(--color-primary);"></i>Proyek per Kategori
                        </div>
                        <div class="ds-card-body">
                            <div style="position: relative; height: 260px; width: 100%;">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top 5 Projects --}}
            <div class="ds-card">
                <div class="ds-card-label">
                    <i class="bi bi-star-fill me-2" style="color: var(--color-secondary);"></i>Top 5 Proyek Terpopuler
                </div>
                <div class="ds-table-wrapper">
                    <table class="ds-table">
                        <thead>
                            <tr>
                                <th class="ps-4">Proyek</th>
                                <th>Author</th>
                                <th>Kategori</th>
                                <th>Likes</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProjects as $project)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="Thumbnail" class="ds-table-thumb me-3" loading="lazy">
                                            <div>
                                                <div class="fw-semibold" style="color: var(--text-primary);">{{ $project->judul }}</div>
                                                <small style="color: var(--text-secondary);">{{ $project->created_at->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="color: var(--text-primary);">{{ $project->mahasiswa->nama_lengkap }}</td>
                                    <td><span class="ds-badge">{{ $project->category->nama_kategori }}</span></td>
                                    <td>
                                        <span class="ds-badge-likes">
                                            <i class="bi bi-heart-fill me-1"></i>{{ $project->likes_count }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.verifications.show', $project) }}" class="ds-btn-ghost ds-btn-sm">Lihat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5" style="color: var(--text-secondary);">
                                        <i class="bi bi-inbox d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                        Belum ada proyek publik yang disetujui.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
/* ── Layout & Typography ── */
.ds-admin-dashboard {
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

/* ── Stat Cards ── */
.ds-stat-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 150ms ease-out;
}
.ds-stat-card:hover {
    transform: translateY(-2px);
}
.ds-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.ds-stat-content {
    display: flex;
    flex-direction: column;
}
.ds-stat-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.ds-stat-value {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
}

/* ── Card ── */
.ds-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
}
.ds-card-label {
    padding: 18px 24px;
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-soft);
}
.ds-card-body {
    padding: 24px !important;
}

/* ── Table ── */
.ds-table-wrapper {
    overflow-x: auto;
}
.ds-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}
.ds-table thead tr {
    background-color: var(--surface-secondary);
}
.ds-table th {
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-secondary);
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-soft);
}
.ds-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-soft);
    vertical-align: middle;
    color: var(--text-primary);
}
.ds-table tbody tr:last-child td {
    border-bottom: none;
}
.ds-table tbody tr:hover {
    background-color: var(--surface-hover);
}
.ds-table-thumb {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--border-soft);
}

/* ── Badges ── */
.ds-badge {
    display: inline-block;
    background-color: var(--surface-secondary);
    color: var(--text-secondary);
    border: 1px solid var(--border-soft);
    border-radius: 8px;
    padding: 4px 12px;
    font-size: 0.78rem;
    font-weight: 500;
}
.ds-badge-likes {
    display: inline-flex;
    align-items: center;
    background-color: #fdf0ef;
    color: #c0392b;
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 0.82rem;
    font-weight: 600;
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
    padding: 8px 16px;
    font-size: 0.82rem;
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
    font-size: 0.78rem !important;
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Status Doughnut Chart — Warm palette
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusData = @json($projectStatusData);
        
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Disetujui', 'Menunggu', 'Ditolak', 'Draft'],
                datasets: [{
                    data: [
                        statusData.approved,
                        statusData.pending,
                        statusData.rejected,
                        statusData.draft
                    ],
                    backgroundColor: ['#5c9e6c', '#c48a5a', '#c0706a', '#a0a0a0'],
                    hoverBackgroundColor: ['#4a8a5a', '#b07a4a', '#b0605a', '#909090'],
                    borderWidth: 2,
                    borderColor: '#FCFAF8'
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 16,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: { family: 'Inter', size: 12 }
                        }
                    }
                }
            }
        });

        // Category Bar Chart — Sage green
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryLabels = @json($categoryLabels);
        const categoryData = @json($categoryData);

        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Jumlah Proyek',
                    data: categoryData,
                    backgroundColor: 'rgba(92, 124, 111, 0.6)',
                    hoverBackgroundColor: 'rgba(92, 124, 111, 0.85)',
                    borderWidth: 0,
                    borderRadius: 8
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { family: 'Inter', size: 11 }
                        },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Inter', size: 11 }
                        }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush

@endsection
