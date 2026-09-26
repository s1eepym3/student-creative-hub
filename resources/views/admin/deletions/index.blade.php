@extends('layouts.app')

@section('content')
<div class="ds-admin-deletions container py-5">
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
                    <h2 class="ds-page-title">Permintaan Hapus Proyek</h2>
                    <p class="ds-page-subtitle">Tinjau proyek yang diajukan untuk dihapus oleh mahasiswa.</p>
                </div>
            </div>

            {{-- Tabs and Table Card --}}
            <div class="ds-card">
                <div class="ds-tabs-wrapper">
                    <a class="ds-tab-link {{ request('status', 'deletion_requested') === 'deletion_requested' ? 'active' : '' }}" href="{{ route('admin.deletions.index', ['status' => 'deletion_requested']) }}">
                        <i class="bi bi-hourglass-split me-1"></i>Menunggu Persetujuan
                    </a>
                    <a class="ds-tab-link {{ request('status') === 'rejected' ? 'active' : '' }}" href="{{ route('admin.deletions.index', ['status' => 'rejected']) }}">
                        <i class="bi bi-x-circle me-1"></i>Ditolak
                    </a>
                </div>

                <div class="ds-card-body p-0">
                    @if($projects->isEmpty())
                        <div class="text-center py-5 px-4" style="color: var(--text-secondary);">
                            <i class="bi bi-inbox d-block mb-3" style="font-size: 2.5rem; opacity: 0.25;"></i>
                            <p class="mb-0">Tidak ada pengajuan penghapusan proyek saat ini.</p>
                        </div>
                    @else
                        <div class="ds-table-wrapper">
                            <table class="ds-table">
                                <thead>
                                    <tr>
                                        <th class="ps-4" style="width: 20%;">Mahasiswa</th>
                                        <th style="width: 25%;">Judul Proyek</th>
                                        <th style="width: 15%;">Kategori</th>
                                        <th style="width: 25%;">Alasan Hapus</th>
                                        <th class="text-end pe-4" style="width: 15%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-semibold" style="color: var(--text-primary);">{{ $project->mahasiswa->nama_lengkap }}</div>
                                                <small style="color: var(--text-secondary);">{{ $project->mahasiswa->nim }}</small>
                                            </td>
                                            <td>
                                                <div style="color: var(--text-primary); font-weight: 500; margin-bottom: 4px;">{{ $project->judul }}</div>
                                                <a href="{{ route('public.project_detail', $project->slug) }}" target="_blank" class="ds-link ds-link-sm"><i class="bi bi-box-arrow-up-right me-1"></i>Lihat Proyek</a>
                                            </td>
                                            <td><span class="ds-badge">{{ $project->category->nama_kategori }}</span></td>
                                            <td>
                                                <div class="ds-truncate-text" title="{{ $project->deletion_reason }}">
                                                    {{ $project->deletion_reason }}
                                                </div>
                                                @if(request('status') === 'rejected')
                                                    <div class="mt-2 ds-warning-text">
                                                        <strong>Ditolak:</strong> {{ $project->delete_rejection_reason }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                @if(request('status', 'deletion_requested') === 'deletion_requested')
                                                    <div class="d-inline-flex gap-2">
                                                        <button type="button" class="ds-btn-ghost ds-btn-sm text-danger border-danger-subtle" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $project->id }}">
                                                            Tolak
                                                        </button>
                                                        <form action="{{ route('admin.deletions.approve', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin setujui hapus? Data media akan DIDELETE SECARA FISIK dan proyek akan di-soft-delete.');">
                                                            @csrf
                                                            <button type="submit" class="ds-btn-danger ds-btn-sm">Setujui Hapus</button>
                                                        </form>
                                                    </div>

                                                    {{-- Modal Reject --}}
                                                    <div class="modal fade text-start" id="rejectModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form action="{{ route('admin.deletions.reject', $project) }}" method="POST">
                                                                @csrf
                                                                <div class="modal-content ds-modal">
                                                                    <div class="ds-modal-header border-danger-subtle bg-danger-subtle">
                                                                        <h5 class="ds-modal-title text-danger"><i class="bi bi-x-circle me-2"></i>Tolak Permintaan Hapus</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="ds-modal-body">
                                                                        <p class="mb-3" style="color: var(--text-secondary); font-size: 0.9rem;">Proyek akan dikembalikan ke status Approved/Aktif.</p>
                                                                        <div class="mb-3">
                                                                            <label for="delete_rejection_reason{{ $project->id }}" class="ds-form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                                            <textarea class="ds-textarea" id="delete_rejection_reason{{ $project->id }}" name="delete_rejection_reason" rows="4" required minlength="10" placeholder="Berikan alasan mengapa proyek ini tidak boleh dihapus..."></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ds-modal-footer">
                                                                        <button type="button" class="ds-btn-ghost" data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="ds-btn-danger">Tolak Permintaan</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="ds-badge" style="background-color: var(--surface-hover);">Resolved</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($projects->hasPages())
                        <div class="px-4 py-3 border-top" style="border-color: var(--border-soft) !important;">
                            {{ $projects->links() }}
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
.ds-admin-deletions { font-family: var(--font-body); }
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
.ds-link { color: var(--color-primary); text-decoration: none; font-weight: 500; }
.ds-link:hover { text-decoration: underline; }
.ds-link-sm { font-size: 0.8rem; }
.ds-truncate-text { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; color: var(--text-secondary); font-size: 0.85rem; line-height: 1.5; }
.ds-warning-text { font-size: 0.8rem; color: #c0392b; background-color: #fdf0ef; padding: 6px 10px; border-radius: 6px; border: 1px solid rgba(192, 57, 43, 0.15); display: inline-block; margin-top: 8px; }

/* Modal */
.ds-modal { border-radius: 24px !important; border: 1px solid var(--border-soft); overflow: hidden; }
.ds-modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border-soft); }
.ds-modal-title { font-family: var(--font-heading); font-weight: 600; font-size: 1.1rem; margin: 0; }
.ds-modal-body { padding: 24px; }
.ds-modal-footer { padding: 16px 24px; border-top: 1px solid var(--border-soft); display: flex; justify-content: flex-end; gap: 8px; }
.ds-form-label { font-size: 0.875rem; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; display: block; }
.ds-textarea { width: 100%; border-radius: var(--radius-input); border: 1px solid var(--border-soft); background-color: var(--surface-primary); color: var(--text-primary); padding: 12px 16px; font-size: 0.9rem; outline: none; font-family: var(--font-body); resize: vertical; transition: border-color 150ms ease-out, box-shadow 150ms ease-out; }
.ds-textarea:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(92, 124, 111, 0.15); }

/* Buttons & Alerts */
.ds-btn-danger { display: inline-flex; align-items: center; justify-content: center; background-color: #c0706a; color: #fff; border: none; border-radius: var(--radius-button); padding: 10px 20px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background-color 150ms ease-out; }
.ds-btn-danger:hover { background-color: #a85a54; color: #fff; }
.ds-btn-ghost { display: inline-flex; align-items: center; justify-content: center; background-color: transparent; color: var(--text-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-button); padding: 10px 20px; font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 150ms ease-out; }
.ds-btn-ghost:hover { background-color: var(--surface-hover); border-color: var(--color-primary); color: var(--color-primary); }
.ds-btn-sm { padding: 6px 14px !important; font-size: 0.78rem !important; }
.ds-alert { padding: 14px 20px; border-radius: var(--radius-button); font-size: 0.875rem; }
.ds-alert-success { background-color: #eef6f0; color: #2e5e3a; border: 1px solid rgba(46, 125, 50, 0.15); }
.ds-alert-danger { background-color: #fdf0ef; color: #8b2020; border: 1px solid rgba(229, 115, 115, 0.15); }
</style>
@endpush

@endsection
