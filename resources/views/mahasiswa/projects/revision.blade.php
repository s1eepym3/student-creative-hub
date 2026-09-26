@extends('layouts.app')

@section('content')
<div class="ds-revision-form container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="ds-page-title">Ajukan Revisi Proyek</h2>
                    <p class="ds-page-subtitle">Perubahan akan ditinjau oleh admin sebelum ditampilkan secara publik.</p>
                </div>
                <a href="{{ route('mahasiswa.dashboard') }}" class="ds-btn-ghost">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>

            {{-- Info Banner --}}
            <div class="ds-info-banner mb-4">
                <i class="bi bi-info-circle-fill me-2"></i>
                Perubahan yang Anda lakukan di sini tidak akan langsung tampil di publik. Admin akan meninjau dan menyetujui revisi Anda terlebih dahulu.
            </div>

            {{-- Form Card --}}
            <div class="ds-card">
                <div class="ds-card-body">
                    <form action="{{ route('mahasiswa.projects.revision.store', $project) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Section: Basic Info --}}
                        <div class="ds-form-section-title mb-3">Informasi Dasar</div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="judul" class="ds-form-label">Judul Proyek <span class="text-danger">*</span></label>
                                <input type="text" class="ds-input @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $project->judul) }}" required>
                                @error('judul')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="category_id" class="ds-form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="ds-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori...</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $project->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="ds-form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="ds-textarea @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="6" required>{{ old('deskripsi', $project->deskripsi) }}</textarea>
                            @error('deskripsi')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="project_url" class="ds-form-label">URL Proyek (Opsional)</label>
                                <input type="url" class="ds-input @error('project_url') is-invalid @enderror" id="project_url" name="project_url" value="{{ old('project_url', $project->project_url) }}" placeholder="https://...">
                                @error('project_url')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="github_url" class="ds-form-label">URL Repository / GitHub (Opsional)</label>
                                <input type="url" class="ds-input @error('github_url') is-invalid @enderror" id="github_url" name="github_url" value="{{ old('github_url', $project->github_url) }}" placeholder="https://github.com/...">
                                @error('github_url')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- Section: Technologies --}}
                        <div class="ds-divider my-4"></div>
                        <div class="ds-form-section-title mb-3">Teknologi yang Digunakan</div>

                        <div id="tech-container">
                            @php
                                $oldTechs = old('technologies', $project->technologies->pluck('technology_name')->toArray());
                            @endphp
                            @if(is_array($oldTechs) && count($oldTechs) > 0)
                                @foreach($oldTechs as $index => $tech)
                                    <div class="ds-tech-row mb-2">
                                        <input type="text" class="ds-input" name="technologies[]" value="{{ $tech }}" placeholder="Contoh: Laravel, React, Figma...">
                                        <button class="ds-btn-icon-danger remove-tech" type="button" title="Hapus"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                @endforeach
                            @else
                                <div class="ds-tech-row mb-2">
                                    <input type="text" class="ds-input" name="technologies[]" placeholder="Contoh: Laravel, React, Figma...">
                                    <button class="ds-btn-icon-danger remove-tech" type="button" title="Hapus"><i class="bi bi-x-lg"></i></button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="ds-btn-add-tech mt-2" id="add-tech">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Teknologi
                        </button>

                        {{-- Section: Media & Thumbnail --}}
                        <div class="ds-divider my-4"></div>
                        <div class="ds-form-section-title mb-3">Media &amp; Thumbnail</div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="thumbnail" class="ds-form-label">Ganti Thumbnail (Opsional)</label>
                                <input class="ds-input @error('thumbnail') is-invalid @enderror" type="file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png">
                                <div class="ds-hint mt-2">Biarkan kosong jika tidak ingin mengubah thumbnail. (Maks. 5 MB, JPG/PNG)</div>
                                @error('thumbnail')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="visibility" class="ds-form-label">Visibilitas <span class="text-danger">*</span></label>
                                <select class="ds-select @error('visibility') is-invalid @enderror" id="visibility" name="visibility" required>
                                    <option value="public" {{ old('visibility', $project->visibility) == 'public' ? 'selected' : '' }}>Publik (Bisa dilihat semua orang)</option>
                                    <option value="private" {{ old('visibility', $project->visibility) == 'private' ? 'selected' : '' }}>Privat (Hanya Anda dan Admin)</option>
                                </select>
                                @error('visibility')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="ds-form-label">Ganti Semua File Media / Screenshot (Opsional)</label>
                            <input class="ds-input @error('media') is-invalid @enderror @error('media.*') is-invalid @enderror" type="file" name="media[]" id="media" multiple accept=".jpg,.jpeg,.png,.mp4">
                            <div class="ds-hint-warning mt-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Jika Anda mengunggah file media di sini, <strong>SEMUA media sebelumnya akan diganti</strong> dengan file yang baru Anda unggah. Biarkan kosong jika tidak ingin mengubah media.
                            </div>
                            @error('media')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                            @error('media.*')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
                        </div>

                        {{-- Submit --}}
                        <div class="d-grid mt-5">
                            <button type="submit" class="ds-btn-primary ds-btn-lg">
                                <i class="bi bi-send me-2"></i>Ajukan Revisi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* ── Layout & Typography ── */
.ds-revision-form {
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
.ds-form-section-title {
    font-family: var(--font-heading);
    font-weight: 600;
    font-size: 1rem;
    color: var(--text-primary);
}
.ds-divider {
    border-top: 1px solid var(--border-soft);
}

/* ── Info Banner ── */
.ds-info-banner {
    background-color: #eef6f2;
    color: #2e5e4a;
    border: 1px solid rgba(92, 124, 111, 0.2);
    border-radius: var(--radius-button);
    padding: 14px 20px;
    font-size: 0.875rem;
}

/* ── Card ── */
.ds-card {
    background-color: var(--surface-primary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-card);
    box-shadow: var(--shadow-soft);
    overflow: hidden;
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
.ds-hint-warning {
    font-size: 0.78rem;
    color: #b57a1e;
    background-color: #fdf6e8;
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid rgba(181, 122, 30, 0.15);
}

/* ── Technology Rows ── */
.ds-tech-row {
    display: flex;
    gap: 8px;
    align-items: center;
}
.ds-tech-row .ds-input {
    flex: 1;
}
.ds-btn-icon-danger {
    background: transparent;
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-button);
    color: #c0392b;
    width: 44px;
    height: 44px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 150ms ease-out;
    flex-shrink: 0;
}
.ds-btn-icon-danger:hover {
    background-color: #fdedeb;
    border-color: #e57373;
}
.ds-btn-add-tech {
    background: transparent;
    border: 1px dashed var(--border-soft);
    border-radius: var(--radius-button);
    color: var(--color-primary);
    padding: 8px 16px;
    font-size: 0.82rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 150ms ease-out;
}
.ds-btn-add-tech:hover {
    background-color: #eef6f2;
    border-color: var(--color-primary);
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
.ds-btn-lg {
    padding: 16px 32px !important;
    font-size: 1rem !important;
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const techContainer = document.getElementById('tech-container');
    const addTechBtn = document.getElementById('add-tech');

    addTechBtn.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'ds-tech-row mb-2';
        row.innerHTML = `
            <input type="text" class="ds-input" name="technologies[]" placeholder="Contoh: Laravel, React, Figma...">
            <button class="ds-btn-icon-danger remove-tech" type="button" title="Hapus"><i class="bi bi-x-lg"></i></button>
        `;
        techContainer.appendChild(row);
    });

    techContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-tech') || e.target.closest('.remove-tech')) {
            const btn = e.target.classList.contains('remove-tech') ? e.target : e.target.closest('.remove-tech');
            const rows = techContainer.querySelectorAll('.ds-tech-row');
            if (rows.length > 1) {
                btn.closest('.ds-tech-row').remove();
            } else {
                btn.closest('.ds-tech-row').querySelector('input').value = '';
            }
        }
    });
});
</script>
@endpush

@endsection
