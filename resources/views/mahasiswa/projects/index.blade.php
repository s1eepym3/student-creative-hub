@extends('layouts.app')

@section('content')
<div class="ds-projects container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            
            {{-- Header/Title --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="ds-page-title">Proyek Saya</h2>
                    <p class="ds-page-subtitle">Kelola repositori karya kreatif dan hasil penelitian Anda.</p>
                </div>
                <a href="{{ route('mahasiswa.projects.create') }}" class="ds-btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Proyek Baru
                </a>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="ds-alert ds-alert-success mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            {{-- Card Container --}}
            <div class="ds-card">
                <div class="ds-card-body p-0">
                    @if($projects->isEmpty())
                        <div class="ds-empty-state text-center py-5">
                            <i class="bi bi-folder2 ds-empty-icon mb-3"></i>
                            <h5 class="ds-empty-title">Proyek Masih Kosong</h5>
                            <p class="ds-empty-desc mb-4">Mulailah memamerkan karya Anda dengan membuat proyek pertama di repositori ini.</p>
                            <a href="{{ route('mahasiswa.projects.create') }}" class="ds-btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Buat Proyek Pertama
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table ds-table align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">Pratinjau</th>
                                        <th>Judul Proyek</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Visibilitas</th>
                                        <th style="width: 260px; text-align: right;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>
                                                @if($project->thumbnail)
                                                    <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="Thumbnail" class="ds-table-thumb" loading="lazy">
                                                @else
                                                    <div class="ds-table-thumb-placeholder"><i class="bi bi-image"></i></div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="ds-table-project-title">{{ $project->judul }}</div>
                                                <small class="text-muted"><i class="bi bi-files me-1"></i> {{ $project->mediaFiles->count() }} Lampiran Media</small>
                                            </td>
                                            <td><span class="ds-badge-category">{{ $project->category->nama_kategori }}</span></td>
                                            <td>
                                                @php
                                                    $statusStyles = [
                                                        'draft'              => ['color' => '#6B6B6B', 'bg' => '#F0EDEA', 'label' => 'Draft'],
                                                        'pending'            => ['color' => '#856404', 'bg' => '#FFF3CD', 'label' => 'Pending'],
                                                        'approved'           => ['color' => '#2E7D32', 'bg' => '#E8F5E9', 'label' => 'Approved'],
                                                        'deletion_requested' => ['color' => '#721C24', 'bg' => '#F8D7DA', 'label' => 'Deletion Requested'],
                                                        'rejected'           => ['color' => '#721C24', 'bg' => '#F8D7DA', 'label' => 'Rejected']
                                                    ];
                                                    $ss = $statusStyles[$project->status] ?? ['color' => '#2E2E2E', 'bg' => '#F8F5F1', 'label' => ucfirst($project->status)];
                                                @endphp
                                                
                                                <span class="ds-badge-level" style="color: {{ $ss['color'] }}; background-color: {{ $ss['bg'] }};">
                                                    {{ $ss['label'] }}
                                                </span>
                                                
                                                @if($project->status === 'approved' && $project->revisions()->where('status', 'pending')->exists())
                                                    <span class="ds-badge-level mt-1 d-block text-center" style="color: #1a56a0; background-color: #F0F5FF; font-size: 0.65rem;">
                                                        Pending Revision Review
                                                    </span>
                                                @endif

                                                @if($project->status === 'approved' && $project->delete_rejection_reason)
                                                    <span class="ds-badge-level mt-1 d-block text-center" style="color: #721C24; background-color: #F8D7DA; font-size: 0.65rem;" title="{{ $project->delete_rejection_reason }}">
                                                        Delete Rejected
                                                    </span>
                                                @endif

                                                @if($project->status === 'rejected')
                                                    <button type="button" class="ds-info-link ms-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $project->id }}">
                                                        <i class="bi bi-info-circle-fill"></i> Info
                                                    </button>

                                                    <!-- Reject Reason Modal -->
                                                    <div class="modal fade" id="rejectModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content ds-modal text-start">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title text-danger"><i class="bi bi-x-circle me-2"></i>Alasan Penolakan</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-wrap text-break">
                                                                    <p class="mb-0 text-secondary">{{ $project->rejection_reason }}</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="ds-btn-ghost" data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($project->visibility === 'public')
                                                    <span class="ds-badge-level" style="color: #1a56a0; background-color: #F0F5FF;">Public</span>
                                                @else
                                                    <span class="ds-badge-level" style="color: #6B6B6B; background-color: #F0EDEA;">Private</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end gap-2">
                                                    @if(in_array($project->status, ['draft', 'rejected']))
                                                        <form action="{{ route('mahasiswa.projects.submit', $project) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="ds-btn-primary ds-btn-sm" onclick="return confirm('Ajukan verifikasi proyek ke admin?')">
                                                                Submit
                                                            </button>
                                                        </form>
                                                        <a href="{{ route('mahasiswa.projects.edit', $project) }}" class="ds-btn-ghost ds-btn-sm">Edit</a>
                                                    @endif

                                                    @if($project->status === 'approved')
                                                        <a href="{{ route('public.project_detail', $project->slug) }}" target="_blank" class="ds-btn-ghost ds-btn-sm">Lihat</a>
                                                        
                                                        @if(!$project->revisions()->where('status', 'pending')->exists())
                                                            <a href="{{ route('mahasiswa.projects.revision.create', $project) }}" class="ds-btn-ghost ds-btn-sm text-warning border-warning-subtle">Revisi</a>
                                                        @endif

                                                        <button type="button" class="ds-btn-ghost ds-btn-sm text-danger border-danger-subtle" data-bs-toggle="modal" data-bs-target="#deleteRequestModal{{ $project->id }}">Hapus</button>

                                                        <!-- Delete Request Modal -->
                                                        <div class="modal fade" id="deleteRequestModal{{ $project->id }}" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered text-start">
                                                                <form action="{{ route('mahasiswa.projects.request_delete', $project) }}" method="POST" class="w-100">
                                                                    @csrf
                                                                    <div class="modal-content ds-modal">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title text-danger"><i class="bi bi-trash me-2"></i>Ajukan Penghapusan Proyek</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p class="ds-hint mb-3">Proyek yang telah disetujui memerlukan persetujuan Admin untuk dihapus secara permanen.</p>
                                                                            <div class="mb-3">
                                                                                <label for="deletion_reason" class="ds-form-label">Alasan Penghapusan (Wajib) <span style="color: #e57373;">*</span></label>
                                                                                <textarea class="ds-textarea" name="deletion_reason" rows="4" required minlength="10" placeholder="Jelaskan alasan penghapusan secara mendetail..."></textarea>
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

                                                    @if($project->status !== 'approved' && $project->status !== 'deletion_requested')
                                                        <form action="{{ route('mahasiswa.projects.destroy', $project) }}" method="POST" class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="ds-icon-btn ds-icon-btn-danger" onclick="return confirm('Hapus proyek ini secara permanen?')" title="Hapus Permanen">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
/* ── Layout & Typography ── */
.ds-projects {
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

/* ── Card & Table ── */
.ds-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
}
.ds-table {
    margin-bottom: 0 !important;
    background-color: var(--surface-primary) !important;
    min-width: 900px;
}
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.ds-table th {
    background-color: var(--bg-section) !important;
    color: var(--text-primary) !important;
    font-family: var(--font-heading);
    font-weight: 600;
    border-bottom: 1px solid var(--border-soft) !important;
    padding: 14px 20px !important;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.ds-table td {
    border-bottom: 1px solid var(--border-soft) !important;
    padding: 14px 20px !important;
    color: var(--text-secondary) !important;
    vertical-align: middle;
}

/* ── Project Specific Assets ── */
.ds-table-thumb {
    width: 80px;
    height: 55px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid var(--border-soft);
}
.ds-table-thumb-placeholder {
    width: 80px;
    height: 55px;
    background-color: var(--surface-secondary);
    border-radius: 8px;
    border: 1px solid var(--border-soft);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    opacity: 0.4;
    font-size: 1.2rem;
}
.ds-table-project-title {
    font-family: var(--font-heading);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
}

/* ── Badges ── */
.ds-badge-category {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    color: var(--text-primary);
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
    display: inline-block;
}
.ds-badge-level {
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 600;
    display: inline-block;
    text-align: center;
}

/* ── Empty State ── */
.ds-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
}
.ds-empty-icon {
    font-size: 2.5rem;
    color: var(--border-soft);
}
.ds-empty-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.15rem;
    color: var(--text-primary);
    margin: 0 0 6px 0;
}
.ds-empty-desc {
    font-size: 0.875rem;
    color: var(--text-secondary);
    max-width: 400px;
}

/* ── Info Links ── */
.ds-info-link {
    background: transparent;
    border: none;
    color: var(--color-secondary);
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
}
.ds-info-link:hover {
    color: var(--text-primary);
}

/* ── Modals & Forms ── */
.ds-modal {
    background-color: var(--surface-modal) !important;
    border-radius: var(--radius-modal) !important;
    border: 1px solid var(--border-soft) !important;
}
.ds-form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
    display: block;
}
.ds-textarea {
    width: 100%;
    border-radius: var(--radius-input);
    border: 1px solid var(--border-soft);
    background-color: var(--surface-primary);
    color: var(--text-primary);
    padding: 12px 16px;
    font-size: 0.9rem;
    outline: none;
    font-family: var(--font-body);
    resize: vertical;
    transition: border-color 150ms ease-out, box-shadow 150ms ease-out;
}
.ds-textarea:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(92, 124, 111, 0.15);
}
.ds-hint {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* ── Icon Buttons ── */
.ds-icon-btn {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    border-radius: 10px;
    color: var(--text-secondary);
    font-size: 0.90rem;
    text-decoration: none;
    transition: all 150ms ease-out;
    cursor: pointer;
}
.ds-icon-btn:hover {
    background-color: var(--surface-hover);
    color: var(--color-primary);
    border-color: var(--color-primary);
}
.ds-icon-btn-danger:hover {
    background-color: #fdf0f0;
    color: #e57373;
    border-color: #e57373;
}

/* ── Alerts ── */
.ds-alert {
    padding: 14px 20px;
    border-radius: var(--radius-button);
    font-size: 0.875rem;
}
.ds-alert-success {
    background-color: var(--color-success);
    color: var(--color-success-text);
    border: 1px solid rgba(46, 125, 50, 0.2);
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
.ds-btn-danger {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #e57373;
    color: #ffffff;
    border: none;
    border-radius: var(--radius-button);
    padding: 10px 20px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 150ms ease-out;
}
.ds-btn-danger:hover {
    background-color: #d32f2f;
    color: #ffffff;
}
.ds-btn-sm {
    padding: 6px 14px !important;
    font-size: 0.8rem !important;
}
</style>
@endpush

@endsection
