@extends('layouts.app')

@section('content')
<div class="ds-projects-edit container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="ds-alert ds-alert-success mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="ds-alert ds-alert-danger mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                </div>
            @endif

            {{-- Rejection Info --}}
            @if($project->status === 'rejected')
                <div class="ds-alert-rejected mb-4">
                    <h5 class="ds-rejected-title"><i class="bi bi-exclamation-octagon-fill me-2"></i>Proyek Ditolak</h5>
                    <p class="ds-rejected-reason mb-3"><strong>Alasan:</strong> {{ $project->rejection_reason }}</p>
                    <div class="ds-divider mb-3"></div>
                    <p class="small text-dark mb-0">Silakan perbaiki data atau media di bawah ini, lalu klik tombol <strong>"Ajukan Verifikasi"</strong> di bagian bawah untuk mengirim ulang.</p>
                </div>
            @endif

            {{-- Navigation Tabs --}}
            <div class="ds-tabs-wrapper mb-4">
                <ul class="nav ds-tabs" id="projectEditTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="ds-tab-btn active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                            <i class="bi bi-info-circle me-2"></i>Informasi &amp; Tags
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="ds-tab-btn" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab">
                            <i class="bi bi-images me-2"></i>Galeri Media ({{ $project->mediaFiles->count() }}/10)
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="projectEditTabsContent">
                
                {{-- Info Tab --}}
                <div class="tab-pane fade show active" id="info" role="tabpanel">
                    <div class="ds-card">
                        <div class="ds-card-label">
                            <i class="bi bi-pencil me-2" style="color: var(--color-primary);"></i>Edit Informasi Proyek
                        </div>
                        <div class="ds-card-body">
                            <form action="{{ route('mahasiswa.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                
                                <div class="mb-4">
                                    <label for="judul" class="ds-form-label">Judul Proyek <span class="text-danger">*</span></label>
                                    <input type="text" name="judul" id="judul" class="ds-input @error('judul') is-invalid @enderror" value="{{ old('judul', $project->judul) }}" required>
                                    @error('judul') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="category_id" class="ds-form-label">Kategori <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="ds-select @error('category_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="visibility" class="ds-form-label">Visibilitas <span class="text-danger">*</span></label>
                                        <select name="visibility" id="visibility" class="ds-select @error('visibility') is-invalid @enderror" required>
                                            <option value="public" {{ old('visibility', $project->visibility) == 'public' ? 'selected' : '' }}>Public (Terlihat oleh Publik)</option>
                                            <option value="private" {{ old('visibility', $project->visibility) == 'private' ? 'selected' : '' }}>Private (Hanya Anda)</option>
                                        </select>
                                        @error('visibility') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="deskripsi" class="ds-form-label">Deskripsi Proyek <span class="text-danger">*</span></label>
                                    <textarea name="deskripsi" id="deskripsi" rows="6" class="ds-textarea @error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi', $project->deskripsi) }}</textarea>
                                    @error('deskripsi') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="row align-items-center g-4 mb-4 ds-current-thumb-box">
                                    <div class="col-md-8">
                                        <label for="thumbnail" class="ds-form-label">Ganti Thumbnail (Opsional)</label>
                                        <input type="file" name="thumbnail" id="thumbnail" class="ds-input @error('thumbnail') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
                                        @error('thumbnail') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                        <div class="ds-hint mt-2">Maksimal ukuran berkas 5 MB. JPG, PNG.</div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="ds-thumb-preview-wrap">
                                            <span class="ds-hint d-block mb-1">Thumbnail Saat Ini</span>
                                            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="Thumbnail" class="ds-edit-thumb-img">
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label for="project_url" class="ds-form-label">Live Demo URL (Opsional)</label>
                                        <input type="url" name="project_url" id="project_url" class="ds-input @error('project_url') is-invalid @enderror" value="{{ old('project_url', $project->project_url) }}" placeholder="https://contoh-demo.com">
                                        @error('project_url') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="github_url" class="ds-form-label">GitHub URL (Opsional)</label>
                                        <input type="url" name="github_url" id="github_url" class="ds-input @error('github_url') is-invalid @enderror" value="{{ old('github_url', $project->github_url) }}" placeholder="https://github.com/username/repository">
                                        @error('github_url') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="technologies_input" class="ds-form-label">Teknologi (Pisahkan dengan koma)</label>
                                    <input type="text" name="technologies_input" id="technologies_input" class="ds-input" value="{{ old('technologies_input', $technologies) }}" placeholder="Contoh: Laravel, React, MySQL, Tailwind">
                                    <div class="ds-hint mt-2">Pisahkan setiap teknologi dengan menggunakan tanda koma.</div>
                                </div>
                                
                                <!-- Hidden inputs generated dynamically for array -->
                                <div id="technologies_container"></div>

                                <div class="d-flex justify-content-between align-items-center mt-5">
                                    <a href="{{ route('mahasiswa.projects.index') }}" class="ds-btn-ghost">Kembali</a>
                                    <button type="submit" class="ds-btn-primary" onclick="prepareTags()">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Media Tab --}}
                <div class="tab-pane fade" id="media" role="tabpanel">
                    <div class="ds-card mb-4">
                        <div class="ds-card-label">
                            <i class="bi bi-plus-lg me-2" style="color: var(--color-primary);"></i>Unggah Media Baru
                        </div>
                        <div class="ds-card-body">
                            @if($project->mediaFiles->count() < 10)
                                <form action="{{ route('mahasiswa.projects.media.store', $project) }}" method="POST" enctype="multipart/form-data" class="row align-items-end g-3">
                                    @csrf
                                    <div class="col-md-9">
                                        <label for="file_media" class="ds-form-label">Pilih Berkas Media (Maks. 20 MB. JPG, PNG, MP4)</label>
                                        <input type="file" name="file_media" id="file_media" class="ds-input @error('file_media') is-invalid @enderror" required>
                                        @error('file_media') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="ds-btn-primary w-100">Upload File</button>
                                    </div>
                                </form>
                            @else
                                <div class="ds-alert ds-alert-warning mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Anda telah mencapai batas maksimum 10 file media untuk proyek ini.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Media Grid --}}
                    <div class="row g-3">
                        @forelse($project->mediaFiles as $media)
                            <div class="col-md-4 col-sm-6">
                                <div class="ds-card ds-gallery-edit-card h-100">
                                    <div class="ds-gallery-img-wrapper">
                                        @if(str_starts_with($media->file_type, 'image/'))
                                            <img src="{{ asset('storage/' . $media->file_path) }}" class="ds-gallery-media" alt="Media" loading="lazy">
                                        @elseif(str_starts_with($media->file_type, 'video/'))
                                            <video src="{{ asset('storage/' . $media->file_path) }}" class="ds-gallery-media" controls></video>
                                        @else
                                            <div class="ds-gallery-placeholder">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="p-3 bg-light border-top text-center mt-auto">
                                        <form action="{{ route('mahasiswa.projects.media.destroy', [$project, $media]) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ds-btn-ghost ds-btn-sm text-danger border-danger-subtle w-100" onclick="return confirm('Hapus media ini?')">Hapus Media</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="ds-empty-state-card text-center p-5 bg-white">
                                    <i class="bi bi-images ds-empty-icon mb-3"></i>
                                    <p class="ds-empty-text">Belum ada media pendukung yang diunggah ke galeri ini.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- Submit for Verification Section --}}
            <div class="ds-card ds-submit-verif-card mt-5">
                <div class="ds-card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1 text-success"><i class="bi bi-send me-2"></i>Siap untuk dipublikasikan?</h5>
                        <p class="mb-0 text-secondary small">Pastikan semua deskripsi, teknologi, dan berkas media sudah benar sebelum diajukan ke admin.</p>
                    </div>
                    <form action="{{ route('mahasiswa.projects.submit', $project) }}" method="POST">
                        @csrf
                        <button type="submit" class="ds-btn-primary px-4 py-3" onclick="return confirm('Kirim proyek untuk diverifikasi?')">
                            Ajukan Verifikasi Proyek
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
/* ── Layout & Typography ── */
.ds-projects-edit {
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

/* ── Divider ── */
.ds-divider {
    border-top: 1px solid var(--border-soft);
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
    padding: 32px !important;
}

/* ── Forms ── */
.ds-form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
    display: block;
}
.ds-input {
    width: 100%;
    border-radius: var(--radius-input);
    border: 1px solid var(--border-soft);
    background-color: var(--surface-primary);
    color: var(--text-primary);
    padding: 12px 16px;
    font-size: 0.9rem;
    outline: none;
    transition: border-color 150ms ease-out, box-shadow 150ms ease-out;
}
.ds-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(92, 124, 111, 0.15);
}
.ds-select {
    width: 100%;
    border-radius: var(--radius-input);
    border: 1px solid var(--border-soft);
    background-color: var(--surface-primary);
    color: var(--text-primary);
    padding: 12px 16px;
    font-size: 0.9rem;
    outline: none;
    cursor: pointer;
    transition: border-color 150ms ease-out, box-shadow 150ms ease-out;
}
.ds-select:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(92, 124, 111, 0.15);
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
    font-size: 0.78rem;
    color: var(--text-secondary);
}

/* ── Current Thumbnail Box ── */
.ds-current-thumb-box {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    padding: 16px;
}
.ds-thumb-preview-wrap {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: 12px;
    padding: 10px;
    display: inline-block;
}
.ds-edit-thumb-img {
    max-height: 80px;
    border-radius: 8px;
    border: 1px solid var(--border-soft);
    object-fit: cover;
}

/* ── Edit Navigation Tabs ── */
.ds-tabs-wrapper {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    border-radius: 14px;
    padding: 6px;
}
.ds-tabs {
    border-bottom: none;
    display: flex;
    gap: 4px;
}
.ds-tab-btn {
    border: none;
    background: transparent;
    padding: 8px 18px;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--text-secondary);
    border-radius: 10px;
    transition: all 150ms;
}
.ds-tab-btn.active {
    background-color: var(--color-primary);
    color: #ffffff;
}
.ds-tab-btn:hover:not(.active) {
    background-color: var(--surface-hover);
    color: var(--text-primary);
}

/* ── Gallery Card in Edit ── */
.ds-gallery-edit-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: 16px;
    overflow: hidden;
}
.ds-gallery-img-wrapper {
    width: 100%;
    aspect-ratio: 16/9;
    overflow: hidden;
    background-color: var(--surface-secondary);
    display: flex;
    align-items: center;
    justify-content: center;
}
.ds-gallery-media {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.ds-gallery-placeholder {
    font-size: 3rem;
    color: var(--text-secondary);
    opacity: 0.3;
}

/* ── Submit Verification Card ── */
.ds-submit-verif-card {
    border-left: 4px solid var(--color-primary) !important;
}

/* ── Rejection Alerts ── */
.ds-alert-rejected {
    background-color: var(--color-danger);
    color: var(--color-danger-text);
    border: 1px solid rgba(229, 115, 115, 0.3);
    padding: 24px;
    border-radius: var(--radius-card);
}
.ds-rejected-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1.15rem;
    color: var(--color-danger-text);
}
.ds-rejected-reason {
    font-size: 0.9rem;
    background-color: rgba(255, 255, 255, 0.5);
    padding: 12px;
    border-radius: 8px;
    border: 1px solid rgba(229, 115, 115, 0.2);
}

/* ── Empty State ── */
.ds-empty-state-card {
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
}
.ds-empty-icon {
    font-size: 2.5rem;
    opacity: 0.3;
}
.ds-empty-text {
    font-size: 0.9rem;
    margin: 0;
}

/* ── Custom Alerts ── */
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
.ds-alert-warning {
    background-color: var(--color-warning);
    color: var(--color-warning-text);
    border: 1px solid rgba(133, 100, 4, 0.2);
}
.ds-alert-danger {
    background-color: var(--color-danger);
    color: var(--color-danger-text);
    border: 1px solid rgba(229, 115, 115, 0.2);
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

@push('scripts')
<script>
function prepareTags() {
    const input = document.getElementById('technologies_input').value;
    const container = document.getElementById('technologies_container');
    container.innerHTML = '';
    
    if(input.trim() !== '') {
        const tags = input.split(',').map(item => item.trim()).filter(item => item !== '');
        tags.forEach(tag => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'technologies[]';
            hidden.value = tag;
            container.appendChild(hidden);
        });
    }
}
</script>
@endpush

@endsection
