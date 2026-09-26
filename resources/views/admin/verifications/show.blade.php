@extends('layouts.app')

@section('content')
<div class="ds-admin-verif-show container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="ds-alert ds-alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="ds-alert ds-alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Action Bar --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <a href="{{ route('admin.verifications.index') }}" class="ds-btn-ghost">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Antrean
                </a>
                <div class="d-flex gap-2 flex-wrap">
                    @if($project->status === 'approved')
                        @if($project->showcase)
                            <form action="{{ route('admin.showcase.destroy', $project) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="ds-btn-secondary-warm"><i class="bi bi-star-fill me-2"></i>Hapus dari Showcase</button>
                            </form>
                        @else
                            <form action="{{ route('admin.showcase.store', $project) }}" method="POST">
                                @csrf
                                <button type="submit" class="ds-btn-ghost"><i class="bi bi-star me-2"></i>Jadikan Showcase</button>
                            </form>
                        @endif
                    @else
                        <button type="button" class="ds-btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Tolak Proyek</button>
                        <form action="{{ route('admin.verifications.approve', $project) }}" method="POST">
                            @csrf
                            <button type="submit" class="ds-btn-success" onclick="return confirm('Setujui proyek ini dan tampilkan secara publik (jika visibilitas public)?')">Setujui Proyek</button>
                        </form>
                    @endif
                    <button type="button" class="ds-btn-icon-danger" data-bs-toggle="modal" data-bs-target="#deleteProjectModal" title="Hapus Proyek">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            {{-- Project Detail Card --}}
            <div class="ds-card mb-4">
                <div class="ds-card-label">
                    <i class="bi bi-file-earmark-text me-2" style="color: var(--color-primary);"></i>Detail Proyek
                </div>
                <div class="ds-card-body">
                    <div class="row mb-4 g-4">
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="Thumbnail" class="ds-detail-thumbnail" loading="lazy">
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-1" style="font-family: var(--font-heading); font-weight: 600; color: var(--text-primary);">{{ $project->judul }}</h4>
                            <p class="mb-4" style="color: var(--text-secondary); font-size: 0.875rem;">Oleh: <strong>{{ $project->mahasiswa->nama_lengkap }}</strong> ({{ $project->mahasiswa->nim }})</p>

                            <div class="ds-detail-grid">
                                <div class="ds-detail-item">
                                    <span class="ds-detail-label">Kategori</span>
                                    <span class="ds-badge">{{ $project->category->nama_kategori }}</span>
                                </div>
                                <div class="ds-detail-item">
                                    <span class="ds-detail-label">Visibilitas</span>
                                    <span class="ds-badge {{ $project->visibility === 'public' ? 'ds-badge-info' : 'ds-badge-dark' }}">{{ ucfirst($project->visibility) }}</span>
                                </div>
                                <div class="ds-detail-item">
                                    <span class="ds-detail-label">Live Demo</span>
                                    @if($project->project_url)
                                        <a href="{{ $project->project_url }}" target="_blank" class="ds-link">{{ $project->project_url }}</a>
                                    @else
                                        <span style="color: var(--text-secondary);">—</span>
                                    @endif
                                </div>
                                <div class="ds-detail-item">
                                    <span class="ds-detail-label">GitHub</span>
                                    @if($project->github_url)
                                        <a href="{{ $project->github_url }}" target="_blank" class="ds-link">{{ $project->github_url }}</a>
                                    @else
                                        <span style="color: var(--text-secondary);">—</span>
                                    @endif
                                </div>
                                <div class="ds-detail-item">
                                    <span class="ds-detail-label">Teknologi</span>
                                    <div class="d-flex flex-wrap gap-1">
                                        @forelse($project->technologies as $tech)
                                            <span class="ds-badge">{{ $tech->technology_name }}</span>
                                        @empty
                                            <span style="color: var(--text-secondary);">—</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="ds-divider my-4"></div>
                    <h5 class="ds-section-heading mb-3">Deskripsi Proyek</h5>
                    <p class="text-break" style="white-space: pre-line; color: var(--text-primary); line-height: 1.7; font-size: 0.9rem;">{{ $project->deskripsi }}</p>

                    {{-- Media Gallery --}}
                    <div class="ds-divider my-4"></div>
                    <h5 class="ds-section-heading mb-3">Galeri Media ({{ $project->mediaFiles->count() }})</h5>
                    <div class="row g-3 mt-2">
                        @forelse($project->mediaFiles as $media)
                            <div class="col-md-3 col-sm-4">
                                <div class="ds-gallery-card">
                                    @if(str_starts_with($media->file_type, 'image/'))
                                        <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $media->file_path) }}" class="ds-gallery-media" alt="Media" loading="lazy">
                                        </a>
                                    @elseif(str_starts_with($media->file_type, 'video/'))
                                        <video src="{{ asset('storage/' . $media->file_path) }}" class="ds-gallery-media" controls></video>
                                    @else
                                        <div class="ds-gallery-placeholder">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12" style="color: var(--text-secondary);">Tidak ada file media tambahan.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ds-modal">
            <form action="{{ route('admin.verifications.reject', $project) }}" method="POST">
                @csrf
                <div class="ds-modal-header">
                    <h5 class="ds-modal-title text-danger"><i class="bi bi-x-circle me-2"></i>Tolak Proyek</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ds-modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="ds-form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" class="ds-textarea" required placeholder="Contoh: Media file mengandung konten yang tidak sesuai..."></textarea>
                    </div>
                </div>
                <div class="ds-modal-footer">
                    <button type="button" class="ds-btn-ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="ds-btn-danger">Tolak Proyek</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Project Modal --}}
<div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ds-modal">
            <form action="{{ route('admin.verifications.destroy', $project) }}" method="POST">
                @csrf @method('DELETE')
                <div class="ds-modal-header">
                    <h5 class="ds-modal-title text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Hapus Proyek</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ds-modal-body">
                    <div class="ds-warning-box mb-3">
                        <strong>Perhatian:</strong> Tindakan ini akan menghapus proyek ini. File media fisik dan thumbnail akan dihapus, tetapi data proyek akan disimpan dalam mode soft-delete untuk keperluan audit.
                    </div>
                    <div class="mb-3">
                        <label for="admin_delete_reason" class="ds-form-label">Alasan Penghapusan <span class="text-danger">*</span></label>
                        <textarea name="admin_delete_reason" id="admin_delete_reason" rows="4" class="ds-textarea" required placeholder="Jelaskan mengapa Anda menghapus proyek ini (min. 10 karakter)..."></textarea>
                    </div>
                </div>
                <div class="ds-modal-footer">
                    <button type="button" class="ds-btn-ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="ds-btn-danger">Hapus Proyek</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.ds-admin-verif-show { font-family: var(--font-body); }

/* Card */
.ds-card { background-color: var(--surface-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-card); box-shadow: var(--shadow-soft); overflow: hidden; }
.ds-card-label { padding: 18px 24px; font-family: var(--font-heading); font-weight: 600; font-size: 0.95rem; color: var(--text-primary); border-bottom: 1px solid var(--border-soft); }
.ds-card-body { padding: 32px !important; }

/* Detail */
.ds-detail-thumbnail { width: 100%; border-radius: 16px; border: 1px solid var(--border-soft); object-fit: cover; aspect-ratio: 16/9; }
.ds-detail-grid { display: flex; flex-direction: column; gap: 12px; }
.ds-detail-item { display: flex; align-items: flex-start; gap: 12px; }
.ds-detail-label { font-size: 0.78rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; min-width: 100px; padding-top: 2px; }
.ds-section-heading { font-family: var(--font-heading); font-weight: 600; font-size: 1rem; color: var(--text-primary); }
.ds-divider { border-top: 1px solid var(--border-soft); }
.ds-link { color: var(--color-primary); text-decoration: none; font-size: 0.875rem; word-break: break-all; }
.ds-link:hover { text-decoration: underline; }

/* Gallery */
.ds-gallery-card { border-radius: 12px; overflow: hidden; border: 1px solid var(--border-soft); background-color: var(--surface-secondary); aspect-ratio: 16/9; }
.ds-gallery-media { width: 100%; height: 100%; object-fit: cover; }
.ds-gallery-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--text-secondary); opacity: 0.25; }

/* Badges */
.ds-badge { display: inline-block; background-color: var(--surface-secondary); color: var(--text-secondary); border: 1px solid var(--border-soft); border-radius: 8px; padding: 4px 12px; font-size: 0.78rem; font-weight: 500; }
.ds-badge-info { background-color: #e8f4fd; color: #1565c0; border-color: rgba(21, 101, 192, 0.15); }
.ds-badge-dark { background-color: #eee; color: #444; border-color: rgba(0,0,0,0.08); }

/* Modal */
.ds-modal { border-radius: 24px !important; border: 1px solid var(--border-soft); overflow: hidden; }
.ds-modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border-soft); }
.ds-modal-title { font-family: var(--font-heading); font-weight: 600; font-size: 1.1rem; margin: 0; }
.ds-modal-body { padding: 24px; }
.ds-modal-footer { padding: 16px 24px; border-top: 1px solid var(--border-soft); display: flex; justify-content: flex-end; gap: 8px; }
.ds-warning-box { background-color: #fdf6e8; color: #856404; padding: 14px 18px; border-radius: var(--radius-button); border: 1px solid rgba(133, 100, 4, 0.15); font-size: 0.875rem; }

/* Form */
.ds-form-label { font-size: 0.875rem; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; display: block; }
.ds-textarea { width: 100%; border-radius: var(--radius-input); border: 1px solid var(--border-soft); background-color: var(--surface-primary); color: var(--text-primary); padding: 12px 16px; font-size: 0.9rem; outline: none; font-family: var(--font-body); resize: vertical; transition: border-color 150ms ease-out, box-shadow 150ms ease-out; }
.ds-textarea:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(92, 124, 111, 0.15); }

/* Alerts */
.ds-alert { padding: 14px 20px; border-radius: var(--radius-button); font-size: 0.875rem; }
.ds-alert-success { background-color: #eef6f0; color: #2e5e3a; border: 1px solid rgba(46, 125, 50, 0.15); }
.ds-alert-danger { background-color: #fdf0ef; color: #8b2020; border: 1px solid rgba(229, 115, 115, 0.15); }

/* Buttons */
.ds-btn-ghost { display: inline-flex; align-items: center; justify-content: center; background-color: transparent; color: var(--text-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-button); padding: 10px 20px; font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 150ms ease-out; }
.ds-btn-ghost:hover { background-color: var(--surface-hover); border-color: var(--color-primary); color: var(--color-primary); }
.ds-btn-success { display: inline-flex; align-items: center; background-color: #5c9e6c; color: #fff; border: none; border-radius: var(--radius-button); padding: 10px 20px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background-color 150ms ease-out; }
.ds-btn-success:hover { background-color: #4a8a5a; color: #fff; }
.ds-btn-danger { display: inline-flex; align-items: center; background-color: #c0706a; color: #fff; border: none; border-radius: var(--radius-button); padding: 10px 20px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background-color 150ms ease-out; }
.ds-btn-danger:hover { background-color: #a85a54; color: #fff; }
.ds-btn-secondary-warm { display: inline-flex; align-items: center; background-color: var(--color-secondary); color: #fff; border: none; border-radius: var(--radius-button); padding: 10px 20px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: background-color 150ms ease-out; }
.ds-btn-secondary-warm:hover { background-color: #b07a4a; color: #fff; }
.ds-btn-icon-danger { background: transparent; border: 1px solid var(--border-soft); border-radius: var(--radius-button); color: #c0392b; width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 150ms ease-out; }
.ds-btn-icon-danger:hover { background-color: #fdf0ef; border-color: #e57373; }
</style>
@endpush

@endsection
