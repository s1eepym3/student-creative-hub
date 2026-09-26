@extends('layouts.app')

@section('content')
<div class="ds-admin-revisions-show container py-5">
    <div class="mb-4">
        <a href="{{ route('admin.revisions.index') }}" class="ds-btn-ghost">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="ds-alert ds-alert-danger mb-4"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}</div>
    @endif

    <div class="row g-4">
        {{-- Original Project --}}
        <div class="col-md-6 mb-4">
            <div class="ds-card h-100" style="background-color: var(--surface-primary);">
                <div class="ds-card-header" style="background-color: var(--surface-secondary);">
                    <h5 class="mb-0" style="color: var(--text-secondary);"><i class="bi bi-clock-history me-2"></i>Versi Original</h5>
                </div>
                <div class="ds-card-body">
                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Judul</label>
                        <p class="ds-detail-value">{{ $originalProject->judul }}</p>
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Kategori</label>
                        <p><span class="ds-badge">{{ $originalProject->category->nama_kategori }}</span></p>
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Deskripsi</label>
                        <div class="ds-desc-box">
                            {!! nl2br(e($originalProject->deskripsi)) !!}
                        </div>
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Thumbnail</label>
                        @if($originalProject->thumbnail)
                            <img src="{{ asset('storage/' . $originalProject->thumbnail) }}" alt="Thumbnail" class="ds-thumbnail">
                        @else
                            <p class="ds-empty-text">Tidak ada thumbnail</p>
                        @endif
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Tautan</label>
                        <div class="d-flex flex-column gap-2 mt-1">
                            <div><span class="text-muted" style="font-size:0.8rem; text-transform:uppercase;">Demo:</span> {!! $originalProject->project_url ? '<a href="'.$originalProject->project_url.'" target="_blank" class="ds-link">'.$originalProject->project_url.'</a>' : '<span class="ds-empty-text">Kosong</span>' !!}</div>
                            <div><span class="text-muted" style="font-size:0.8rem; text-transform:uppercase;">Github:</span> {!! $originalProject->github_url ? '<a href="'.$originalProject->github_url.'" target="_blank" class="ds-link">'.$originalProject->github_url.'</a>' : '<span class="ds-empty-text">Kosong</span>' !!}</div>
                        </div>
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Teknologi</label>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @forelse($originalProject->technologies as $tech)
                                <span class="ds-badge">{{ $tech->technology_name }}</span>
                            @empty
                                <span class="ds-empty-text">Kosong</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Media / Screenshot</label>
                        <div class="d-flex flex-column gap-1 mt-1">
                            @forelse($originalProject->mediaFiles as $media)
                                <div class="ds-file-item"><i class="bi bi-file-earmark me-2"></i> {{ $media->file_name }}</div>
                            @empty
                                <div class="ds-empty-text">Kosong</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revised Project --}}
        <div class="col-md-6 mb-4">
            <div class="ds-card ds-card-highlight h-100">
                <div class="ds-card-header ds-card-header-highlight">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Versi Revisi (Pengajuan)</h5>
                </div>
                <div class="ds-card-body">
                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Judul</label>
                        <p class="ds-detail-value {{ $revision->judul !== $originalProject->judul ? 'ds-diff-text' : '' }}">{{ $revision->judul }}</p>
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Kategori</label>
                        <p><span class="ds-badge {{ $revision->category_id !== $originalProject->category_id ? 'ds-diff-badge' : '' }}">{{ $revision->category ? $revision->category->nama_kategori : '-' }}</span></p>
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Deskripsi</label>
                        <div class="ds-desc-box {{ $revision->deskripsi !== $originalProject->deskripsi ? 'ds-diff-border' : '' }}">
                            {!! nl2br(e($revision->deskripsi)) !!}
                        </div>
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Thumbnail Baru</label>
                        @if($revision->thumbnail)
                            <img src="{{ asset('storage/' . $revision->thumbnail) }}" alt="Thumbnail" class="ds-thumbnail {{ $revision->thumbnail !== $originalProject->thumbnail ? 'ds-diff-border' : '' }}">
                        @else
                            <p class="ds-empty-text">Tidak ada thumbnail baru (Menggunakan yang lama)</p>
                        @endif
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Tautan</label>
                        <div class="d-flex flex-column gap-2 mt-1">
                            <div class="{{ $revision->project_url !== $originalProject->project_url ? 'ds-diff-text' : '' }}"><span class="text-muted" style="font-size:0.8rem; text-transform:uppercase;">Demo:</span> {!! $revision->project_url ? '<a href="'.$revision->project_url.'" target="_blank" class="ds-link">'.$revision->project_url.'</a>' : '<span class="ds-empty-text">Kosong</span>' !!}</div>
                            <div class="{{ $revision->github_url !== $originalProject->github_url ? 'ds-diff-text' : '' }}"><span class="text-muted" style="font-size:0.8rem; text-transform:uppercase;">Github:</span> {!! $revision->github_url ? '<a href="'.$revision->github_url.'" target="_blank" class="ds-link">'.$revision->github_url.'</a>' : '<span class="ds-empty-text">Kosong</span>' !!}</div>
                        </div>
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Teknologi</label>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @forelse($revision->technologies as $tech)
                                <span class="ds-badge ds-badge-highlight">{{ $tech->technology_name }}</span>
                            @empty
                                <span class="ds-empty-text">Kosong</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="ds-detail-group">
                        <label class="ds-detail-label">Media / Screenshot Baru</label>
                        <div class="d-flex flex-column gap-1 mt-1">
                            @forelse($revision->mediaFiles as $media)
                                <div class="ds-file-item ds-diff-text"><i class="bi bi-file-earmark-check me-2"></i> {{ $media->file_name }}</div>
                            @empty
                                <div class="ds-empty-text">Tidak ada perubahan media tambahan</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <div class="ds-card-footer">
                    @if($revision->status === 'pending')
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <button type="button" class="ds-btn-ghost text-danger border-danger-subtle" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                Tolak Revisi
                            </button>
                            <form action="{{ route('admin.revisions.approve', $revision) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyetujui revisi ini? Proyek publik akan segera diperbarui.');">
                                @csrf
                                <button type="submit" class="ds-btn-success">Setujui & Terapkan Revisi</button>
                            </form>
                        </div>
                    @elseif($revision->status === 'rejected')
                        <div class="ds-status-banner ds-status-danger">
                            <strong>Ditolak:</strong> {{ $revision->rejection_reason }}
                        </div>
                    @else
                        <div class="ds-status-banner ds-status-success">
                            <i class="bi bi-check-circle me-2"></i><strong>Revisi telah disetujui dan diterapkan.</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Reject --}}
@if($revision->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.revisions.reject', $revision) }}" method="POST">
            @csrf
            <div class="modal-content ds-modal">
                <div class="ds-modal-header border-danger-subtle bg-danger-subtle">
                    <h5 class="ds-modal-title text-danger" id="rejectModalLabel"><i class="bi bi-x-circle me-2"></i>Tolak Pengajuan Revisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ds-modal-body">
                    <p class="mb-3" style="color: var(--text-secondary); font-size: 0.9rem;">Revisi ini tidak akan diterapkan ke proyek aslinya.</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="ds-form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="ds-textarea" id="rejection_reason" name="rejection_reason" rows="4" required minlength="10" placeholder="Jelaskan secara detail mengapa revisi ini ditolak..."></textarea>
                    </div>
                </div>
                <div class="ds-modal-footer">
                    <button type="button" class="ds-btn-ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="ds-btn-danger">Tolak Revisi</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@push('styles')
<style>
.ds-admin-revisions-show { font-family: var(--font-body); }

/* Card */
.ds-card { background-color: var(--surface-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-card); box-shadow: var(--shadow-soft); overflow: hidden; display: flex; flex-direction: column; }
.ds-card-highlight { border-color: rgba(92, 124, 111, 0.3); background-color: #fbfdfc; }
.ds-card-header { padding: 16px 24px; border-bottom: 1px solid var(--border-soft); }
.ds-card-header h5 { font-family: var(--font-heading); font-size: 1rem; font-weight: 600; }
.ds-card-header-highlight { background-color: rgba(92, 124, 111, 0.08); color: var(--color-primary); }
.ds-card-body { padding: 24px; flex-grow: 1; }
.ds-card-footer { padding: 20px 24px; border-top: 1px solid var(--border-soft); background-color: var(--surface-secondary); }

/* Detail Elements */
.ds-detail-group { margin-bottom: 20px; }
.ds-detail-label { display: block; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); margin-bottom: 8px; }
.ds-detail-value { font-size: 0.95rem; color: var(--text-primary); margin: 0; }
.ds-desc-box { padding: 16px; background-color: var(--surface-secondary); border-radius: 12px; font-size: 0.9rem; color: var(--text-primary); line-height: 1.6; max-height: 250px; overflow-y: auto; border: 1px solid transparent; }
.ds-thumbnail { width: 100%; max-height: 180px; object-fit: cover; border-radius: 12px; border: 1px solid var(--border-soft); }
.ds-file-item { font-size: 0.85rem; color: var(--text-secondary); background-color: var(--surface-secondary); padding: 8px 12px; border-radius: 8px; }
.ds-empty-text { font-size: 0.85rem; color: var(--text-secondary); font-style: italic; }
.ds-link { color: var(--color-primary); text-decoration: none; word-break: break-all; }
.ds-link:hover { text-decoration: underline; }

/* Badges */
.ds-badge { display: inline-block; background-color: var(--surface-secondary); color: var(--text-secondary); border: 1px solid var(--border-soft); border-radius: 8px; padding: 4px 10px; font-size: 0.75rem; font-weight: 500; }
.ds-badge-highlight { background-color: rgba(92, 124, 111, 0.1); color: var(--color-primary); border-color: rgba(92, 124, 111, 0.2); }

/* Diff Styling */
.ds-diff-text { color: #2e7d32; font-weight: 600; }
.ds-diff-border { border-color: #4caf50; background-color: #f1f8e9; }
.ds-diff-badge { border-color: #4caf50; color: #2e7d32; background-color: #e8f5e9; font-weight: 600; }

/* Modals & Forms */
.ds-modal { border-radius: 24px !important; border: 1px solid var(--border-soft); overflow: hidden; }
.ds-modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border-soft); }
.ds-modal-title { font-family: var(--font-heading); font-weight: 600; font-size: 1.1rem; margin: 0; }
.ds-modal-body { padding: 24px; }
.ds-modal-footer { padding: 16px 24px; border-top: 1px solid var(--border-soft); display: flex; justify-content: flex-end; gap: 8px; }
.ds-form-label { font-size: 0.875rem; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; display: block; }
.ds-textarea { width: 100%; border-radius: var(--radius-input); border: 1px solid var(--border-soft); background-color: var(--surface-primary); color: var(--text-primary); padding: 12px 16px; font-size: 0.9rem; outline: none; font-family: var(--font-body); resize: vertical; transition: border-color 150ms ease-out, box-shadow 150ms ease-out; }
.ds-textarea:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(92, 124, 111, 0.15); }

/* Buttons & Banners */
.ds-btn-ghost { display: inline-flex; align-items: center; justify-content: center; background-color: transparent; color: var(--text-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-button); padding: 10px 20px; font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 150ms ease-out; }
.ds-btn-ghost:hover { background-color: var(--surface-hover); border-color: var(--color-primary); color: var(--color-primary); }
.ds-btn-success { display: inline-flex; align-items: center; justify-content: center; background-color: #5c9e6c; color: #fff; border: none; border-radius: var(--radius-button); padding: 10px 20px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background-color 150ms ease-out; }
.ds-btn-success:hover { background-color: #4a8a5a; color: #fff; }
.ds-btn-danger { display: inline-flex; align-items: center; justify-content: center; background-color: #c0706a; color: #fff; border: none; border-radius: var(--radius-button); padding: 10px 20px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background-color 150ms ease-out; }
.ds-btn-danger:hover { background-color: #a85a54; color: #fff; }

.ds-status-banner { padding: 14px 20px; border-radius: var(--radius-button); font-size: 0.9rem; }
.ds-status-danger { background-color: #fdf0ef; color: #8b2020; border: 1px solid rgba(229, 115, 115, 0.15); }
.ds-status-success { background-color: #eef6f0; color: #2e5e3a; border: 1px solid rgba(46, 125, 50, 0.15); }
.ds-alert { padding: 14px 20px; border-radius: var(--radius-button); font-size: 0.875rem; }
.ds-alert-danger { background-color: #fdf0ef; color: #8b2020; border: 1px solid rgba(229, 115, 115, 0.15); }
.ds-alert-success { background-color: #eef6f0; color: #2e5e3a; border: 1px solid rgba(46, 125, 50, 0.15); }
</style>
@endpush

@endsection
