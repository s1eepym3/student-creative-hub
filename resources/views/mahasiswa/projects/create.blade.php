@extends('layouts.app')

@section('content')
<div class="ds-projects-form container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Header/Title --}}
            <div class="mb-4 text-center">
                <h2 class="ds-page-title">Buat Proyek Baru</h2>
                <p class="ds-page-subtitle">Publikasikan karya kreatif, riset, atau proyek IT terbaik Anda ke repositori portfolio.</p>
            </div>

            {{-- Card Container --}}
            <div class="ds-card">
                <div class="ds-card-body">
                    <form action="{{ route('mahasiswa.projects.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="judul" class="ds-form-label">Judul Proyek <span class="text-danger">*</span></label>
                            <input type="text" name="judul" id="judul" class="ds-input @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Contoh: Sistem Pendeteksi Kualitas Air Pintar" required>
                            @error('judul') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="category_id" class="ds-form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="ds-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="visibility" class="ds-form-label">Visibilitas <span class="text-danger">*</span></label>
                                <select name="visibility" id="visibility" class="ds-select @error('visibility') is-invalid @enderror" required>
                                    <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public (Terlihat oleh Publik)</option>
                                    <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private (Hanya Anda)</option>
                                </select>
                                @error('visibility') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="ds-form-label">Deskripsi Proyek <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" id="deskripsi" rows="6" class="ds-textarea @error('deskripsi') is-invalid @enderror" placeholder="Ceritakan latar belakang, fitur utama, dan solusi yang ditawarkan oleh proyek ini..." required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="thumbnail" class="ds-form-label">Thumbnail Proyek <span class="text-danger">*</span></label>
                            <input type="file" name="thumbnail" id="thumbnail" class="ds-input @error('thumbnail') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg" required>
                            @error('thumbnail') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            <div class="ds-hint mt-2">Format: JPG, JPEG, PNG (maks. 5 MB). Digunakan sebagai sampul proyek.</div>
                        </div>

                        <div class="mb-4">
                            <label for="media" class="ds-form-label">Galeri Media Pendukung <span class="text-danger">*</span></label>
                            <input type="file" name="media[]" id="media" class="ds-input @error('media') is-invalid @enderror" accept="image/*,video/*" multiple required>
                            @error('media') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            <div class="ds-hint mt-2">Unggah tangkapan layar (screenshot) atau rekaman demo (maks. 5 berkas, masing-masing maks. 20 MB).</div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="project_url" class="ds-form-label">Live Demo URL (Opsional)</label>
                                <input type="url" name="project_url" id="project_url" class="ds-input @error('project_url') is-invalid @enderror" value="{{ old('project_url') }}" placeholder="https://contoh-demo.com">
                                @error('project_url') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="github_url" class="ds-form-label">GitHub URL (Opsional)</label>
                                <input type="url" name="github_url" id="github_url" class="ds-input @error('github_url') is-invalid @enderror" value="{{ old('github_url') }}" placeholder="https://github.com/username/repository">
                                @error('github_url') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="technologies_input" class="ds-form-label">Teknologi yang Digunakan (Pisahkan dengan koma)</label>
                            <input type="text" name="technologies_input" id="technologies_input" class="ds-input" value="{{ old('technologies_input') }}" placeholder="Contoh: Laravel, React, MySQL, Tailwind">
                            <div class="ds-hint mt-2">Gunakan koma untuk memisahkan setiap kata kunci teknologi.</div>
                        </div>
                        
                        <!-- Hidden inputs generated dynamically for array -->
                        <div id="technologies_container"></div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('mahasiswa.projects.index') }}" class="ds-btn-ghost">Batal</a>
                            <button type="submit" class="ds-btn-primary" onclick="prepareTags()">Simpan sebagai Draft</button>
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
.ds-projects-form {
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
