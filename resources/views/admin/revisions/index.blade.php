@extends('layouts.app')

@section('content')
<div class="ds-admin-revisions container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="ds-alert ds-alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="ds-alert ds-alert-danger mb-4"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}</div>
            @endif

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="ds-page-title">Revisi Proyek</h2>
                    <p class="ds-page-subtitle">Tinjau pengajuan revisi pada proyek mahasiswa yang sudah diverifikasi sebelumnya.</p>
                </div>
            </div>

            {{-- Card --}}
            <div class="ds-card">
                <div class="ds-tabs-wrapper">
                    <a class="ds-tab-link {{ request('status', 'pending') === 'pending' ? 'active' : '' }}" href="{{ route('admin.revisions.index', ['status' => 'pending']) }}">
                        <i class="bi bi-hourglass-split me-1"></i>Menunggu
                    </a>
                    <a class="ds-tab-link {{ request('status') === 'approved' ? 'active' : '' }}" href="{{ route('admin.revisions.index', ['status' => 'approved']) }}">
                        <i class="bi bi-check-circle me-1"></i>Disetujui
                    </a>
                    <a class="ds-tab-link {{ request('status') === 'rejected' ? 'active' : '' }}" href="{{ route('admin.revisions.index', ['status' => 'rejected']) }}">
                        <i class="bi bi-x-circle me-1"></i>Ditolak
                    </a>
                </div>

                <div class="ds-card-body p-0">
                    @if($revisions->isEmpty())
                        <div class="text-center py-5 px-4" style="color: var(--text-secondary);">
                            <i class="bi bi-inbox d-block mb-3" style="font-size: 2.5rem; opacity: 0.25;"></i>
                            <p class="mb-0">Tidak ada pengajuan revisi saat ini.</p>
                        </div>
                    @else
                        <div class="ds-table-wrapper">
                            <table class="ds-table">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Mahasiswa</th>
                                        <th>Judul Proyek (Revisi)</th>
                                        <th>Kategori Baru</th>
                                        <th>Tgl Pengajuan</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($revisions as $revision)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold" style="color: var(--text-primary);">{{ $revision->project->mahasiswa->nama_lengkap }}</div>
                                                <small style="color: var(--text-secondary);">{{ $revision->project->mahasiswa->nim }}</small>
                                            </td>
                                            <td>
                                                <div style="color: var(--text-primary); font-weight: 500; margin-bottom: 4px;">{{ $revision->judul }}</div>
                                                <small style="color: var(--text-secondary);">Asli: {{ $revision->project->judul }}</small>
                                            </td>
                                            <td><span class="ds-badge">{{ $revision->category ? $revision->category->nama_kategori : '-' }}</span></td>
                                            <td style="color: var(--text-secondary); font-size: 0.82rem;">{{ $revision->created_at->format('d M Y, H:i') }}</td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('admin.revisions.show', $revision) }}" class="ds-btn-primary ds-btn-sm">Review</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($revisions->hasPages())
                        <div class="px-4 py-3 border-top" style="border-color: var(--border-soft) !important;">
                            {{ $revisions->links() }}
                        </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
.ds-admin-revisions { font-family: var(--font-body); }
.ds-page-title { font-family: var(--font-heading); font-weight: 600; font-size: 1.5rem; color: var(--text-primary); margin: 0; }
.ds-page-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin: 4px 0 0; }
.ds-card { background-color: var(--surface-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-card); box-shadow: var(--shadow-soft); overflow: hidden; }

/* Tabs */
.ds-tabs-wrapper { display: flex; gap: 4px; padding: 12px 16px; border-bottom: 1px solid var(--border-soft); background-color: var(--surface-secondary); flex-wrap: wrap; }
.ds-tab-link { display: inline-flex; align-items: center; padding: 8px 16px; font-size: 0.82rem; font-weight: 500; color: var(--text-secondary); border-radius: 10px; text-decoration: none; transition: all 150ms; }
.ds-tab-link.active { background-color: var(--color-primary); color: #fff; }
.ds-tab-link:hover:not(.active) { background-color: var(--surface-hover); color: var(--text-primary); }

/* Table */
.ds-table-wrapper { overflow-x: auto; }
.ds-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
.ds-table thead tr { background-color: var(--surface-secondary); }
.ds-table th { font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); padding: 14px 16px; border-bottom: 1px solid var(--border-soft); }
.ds-table td { padding: 14px 16px; border-bottom: 1px solid var(--border-soft); vertical-align: middle; }
.ds-table tbody tr:last-child td { border-bottom: none; }
.ds-table tbody tr:hover { background-color: var(--surface-hover); }

/* Elements */
.ds-badge { display: inline-block; background-color: var(--surface-secondary); color: var(--text-secondary); border: 1px solid var(--border-soft); border-radius: 8px; padding: 4px 12px; font-size: 0.78rem; font-weight: 500; }
.ds-btn-primary { display: inline-flex; align-items: center; background-color: var(--color-primary); color: #fff; border: none; border-radius: var(--radius-button); padding: 8px 16px; font-size: 0.82rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: background-color 150ms ease-out; }
.ds-btn-primary:hover { background-color: #4a6459; color: #fff; }
.ds-btn-sm { padding: 6px 14px !important; font-size: 0.78rem !important; }

/* Alerts */
.ds-alert { padding: 14px 20px; border-radius: var(--radius-button); font-size: 0.875rem; }
.ds-alert-success { background-color: #eef6f0; color: #2e5e3a; border: 1px solid rgba(46, 125, 50, 0.15); }
.ds-alert-danger { background-color: #fdf0ef; color: #8b2020; border: 1px solid rgba(229, 115, 115, 0.15); }
</style>
@endpush

@endsection
