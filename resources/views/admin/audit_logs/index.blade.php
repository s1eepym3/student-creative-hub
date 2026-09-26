@extends('layouts.app')

@section('content')
<div class="ds-admin-audit container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="ds-page-title">Log Aktivitas (Audit Logs)</h2>
                    <p class="ds-page-subtitle">Riwayat aktivitas sistem Student Creative Hub.</p>
                </div>
            </div>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('admin.audit_logs.index') }}" class="mb-4">
                <div class="ds-search-bar">
                    <i class="bi bi-search ds-search-icon"></i>
                    <input type="text" name="q" value="{{ request('q') }}" class="ds-search-input"
                        placeholder="Cari berdasarkan aksi, deskripsi, IP, atau nama user...">
                    <button class="ds-btn-primary ds-btn-search" type="submit">Cari</button>
                    @if(request('q'))
                        <a href="{{ route('admin.audit_logs.index') }}" class="ds-btn-ghost ms-2">Reset</a>
                    @endif
                </div>
            </form>

            {{-- Table Card --}}
            <div class="ds-card">
                <div class="ds-table-wrapper">
                    <table class="ds-table">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 15%">Waktu</th>
                                <th style="width: 20%">Pengguna</th>
                                <th style="width: 20%">Aksi</th>
                                <th style="width: 33%">Deskripsi</th>
                                <th class="pe-4" style="width: 12%">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold" style="color: var(--text-primary); font-size: 0.85rem;">{{ $log->created_at->format('d M Y') }}</div>
                                        <small style="color: var(--text-secondary);">{{ $log->created_at->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        @if($log->user)
                                            <div class="fw-semibold" style="color: var(--text-primary);">{{ $log->user->name }}</div>
                                            <span class="ds-badge ds-badge-role">{{ $log->user->role }}</span>
                                        @else
                                            <span style="color: var(--text-secondary); font-style: italic;">Sistem / Guest</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            // Mapping actions to specific warm/soft token colors or custom tints
                                            $actionStyles = [
                                                'USER_LOGIN' => 'success', 'USER_LOGOUT' => 'secondary',
                                                'USER_REGISTER' => 'primary', 'USER_APPROVED' => 'success',
                                                'USER_SUSPENDED' => 'warning', 'USER_ACTIVATED' => 'success',
                                                'USER_DELETED' => 'danger', 'PROJECT_CREATED' => 'primary',
                                                'PROJECT_SUBMITTED' => 'primary', 'PROJECT_APPROVED' => 'success',
                                                'PROJECT_REJECTED' => 'danger', 'PROJECT_UPDATED' => 'secondary',
                                                'PROJECT_DELETED' => 'danger', 'PROJECT_DELETED_BY_ADMIN' => 'danger',
                                                'SHOWCASE_ADDED' => 'warning', 'SHOWCASE_REMOVED' => 'secondary',
                                                'PROJECT_LIKED' => 'danger', 'PROJECT_UNLIKED' => 'secondary',
                                                'QR_GENERATED' => 'primary',
                                            ];
                                            $style = $actionStyles[$log->action] ?? 'secondary';
                                        @endphp
                                        <span class="ds-action-badge ds-action-{{ $style }}">
                                            {{ str_replace('_', ' ', $log->action) }}
                                        </span>
                                    </td>
                                    <td class="text-break" style="font-size: 0.85rem; color: var(--text-primary); max-width: 300px;">
                                        {{ $log->description }}
                                    </td>
                                    <td class="pe-4">
                                        <code style="background-color: var(--surface-secondary); padding: 3px 6px; border-radius: 4px; font-size: 0.8rem; color: var(--text-secondary);">
                                            {{ $log->ip_address ?? '-' }}
                                        </code>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5" style="color: var(--text-secondary);">
                                        <i class="bi bi-journal-x d-block mb-3" style="font-size: 2.5rem; opacity: 0.25;"></i>
                                        Tidak ada entri log yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($logs->hasPages())
                    <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-color: var(--border-soft) !important;">
                        <small style="color: var(--text-secondary);">Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} entri</small>
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
.ds-admin-audit { font-family: var(--font-body); }
.ds-page-title { font-family: var(--font-heading); font-weight: 600; font-size: 1.5rem; color: var(--text-primary); margin: 0; }
.ds-page-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin: 4px 0 0; }

/* Card */
.ds-card { background-color: var(--surface-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-card); box-shadow: var(--shadow-soft); overflow: hidden; }

/* Table */
.ds-table-wrapper { overflow-x: auto; }
.ds-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
.ds-table thead tr { background-color: var(--surface-secondary); }
.ds-table th { font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); padding: 14px 16px; border-bottom: 1px solid var(--border-soft); }
.ds-table td { padding: 14px 16px; border-bottom: 1px solid var(--border-soft); vertical-align: middle; }
.ds-table tbody tr:last-child td { border-bottom: none; }
.ds-table tbody tr:hover { background-color: var(--surface-hover); }

/* Badges */
.ds-badge { display: inline-block; background-color: var(--surface-secondary); color: var(--text-secondary); border: 1px solid var(--border-soft); border-radius: 8px; padding: 4px 12px; font-size: 0.78rem; font-weight: 500; }
.ds-badge-role { padding: 2px 8px; font-size: 0.7rem; margin-top: 4px; border-radius: 4px; }
.ds-action-badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; font-family: 'Inter', sans-serif; letter-spacing: 0.5px; border: 1px solid transparent; }
.ds-action-success { background-color: #eef6f0; color: #2e7d32; border-color: rgba(46, 125, 50, 0.15); }
.ds-action-primary { background-color: #eef3f6; color: var(--color-primary); border-color: rgba(92, 124, 111, 0.15); }
.ds-action-warning { background-color: #fdf6e8; color: #b57a1e; border-color: rgba(181, 122, 30, 0.15); }
.ds-action-danger { background-color: #fdf0ef; color: #c0392b; border-color: rgba(192, 57, 43, 0.15); }
.ds-action-secondary { background-color: #f5f5f5; color: #666; border-color: rgba(0,0,0,0.1); }

/* Search Bar */
.ds-search-bar { display: flex; align-items: center; background-color: var(--surface-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-input); padding: 4px; box-shadow: var(--shadow-soft); max-width: 600px; }
.ds-search-icon { color: var(--text-secondary); padding: 0 12px; font-size: 1.1rem; }
.ds-search-input { flex-grow: 1; border: none; background: transparent; outline: none; padding: 8px 0; font-size: 0.9rem; color: var(--text-primary); }
.ds-btn-search { padding: 8px 16px; border-radius: calc(var(--radius-input) - 4px); }

/* Buttons */
.ds-btn-primary { display: inline-flex; align-items: center; justify-content: center; background-color: var(--color-primary); color: #fff; border: none; border-radius: var(--radius-button); font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: background-color 150ms ease-out; }
.ds-btn-primary:hover { background-color: #4a6459; color: #fff; }
.ds-btn-ghost { display: inline-flex; align-items: center; justify-content: center; background-color: transparent; color: var(--text-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-button); padding: 8px 16px; font-size: 0.82rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 150ms ease-out; }
.ds-btn-ghost:hover { background-color: var(--surface-hover); border-color: var(--color-primary); color: var(--color-primary); }
</style>
@endpush

@endsection
