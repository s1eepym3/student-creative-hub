@extends('layouts.app')

@section('content')
<div class="ds-profile-edit container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            {{-- Header/Title --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="ds-page-title">Edit Profil</h2>
                    <p class="ds-page-subtitle">Kelola informasi publik Anda agar mudah dilihat oleh pengunjung.</p>
                </div>
                <div>
                    @php
                        $badgeBg = $completion >= 80 ? 'var(--color-success)' : ($completion >= 50 ? 'var(--color-warning)' : 'var(--color-danger)');
                        $badgeText = $completion >= 80 ? 'var(--color-success-text)' : ($completion >= 50 ? 'var(--color-warning-text)' : 'var(--color-danger-text)');
                    @endphp
                    <span class="ds-completion-badge" style="background-color: {{ $badgeBg }}; color: {{ $badgeText }};">
                        Kelengkapan Profil: {{ $completion }}%
                    </span>
                </div>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="ds-alert ds-alert-success mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            {{-- Card Container --}}
            <div class="ds-card">
                <div class="ds-card-body">
                    <form action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        {{-- Avatar Profile Photo Section --}}
                        <div class="row align-items-center mb-5 g-4">
                            <div class="col-md-3 text-center">
                                <div class="ds-avatar-upload-container mb-2">
                                    @if($mahasiswa->foto_profil)
                                        <img src="{{ Storage::url($mahasiswa->foto_profil) }}" alt="Profile" class="ds-upload-avatar">
                                    @else
                                        <div class="ds-upload-avatar-placeholder">
                                            <span>{{ strtoupper(substr($mahasiswa->nama_lengkap, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <label for="foto_profil" class="ds-btn-ghost ds-btn-sm d-inline-block">
                                        <i class="bi bi-camera me-1"></i> Ubah Foto
                                    </label>
                                    <input type="file" name="foto_profil" id="foto_profil" class="d-none" accept="image/jpeg,image/png">
                                    @error('foto_profil')
                                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="ds-hint mt-2">JPG, PNG (maks. 5 MB)</div>
                                </div>
                            </div>
                            
                            {{-- Biodata Inputs --}}
                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nama_lengkap" class="ds-form-label">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" id="nama_lengkap"
                                               class="ds-input @error('nama_lengkap') is-invalid @enderror"
                                               value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required>
                                        @error('nama_lengkap')
                                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="ds-form-label">NIM</label>
                                        <input type="text" class="ds-input-readonly" value="{{ $mahasiswa->nim }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="prodi" class="ds-form-label">Program Studi</label>
                                        <input type="text" name="prodi" id="prodi"
                                               class="ds-input @error('prodi') is-invalid @enderror"
                                               value="{{ old('prodi', $mahasiswa->prodi) }}">
                                        @error('prodi')
                                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="angkatan" class="ds-form-label">Angkatan</label>
                                        <input type="text" name="angkatan" id="angkatan"
                                               class="ds-input @error('angkatan') is-invalid @enderror"
                                               value="{{ old('angkatan', $mahasiswa->angkatan) }}">
                                        @error('angkatan')
                                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ds-divider mb-4"></div>

                        {{-- Biography Section --}}
                        <div class="mb-4">
                            <label for="bio" class="ds-form-label">Bio Singkat</label>
                            <textarea name="bio" id="bio" rows="4"
                                      class="ds-textarea @error('bio') is-invalid @enderror"
                                      placeholder="Tuliskan biografi singkat tentang diri Anda, keahlian utama, dan minat karir... Thickness...">{{ old('bio', $mahasiswa->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="ds-divider mb-4"></div>

                        {{-- Social Media Links --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="github" class="ds-form-label"><i class="bi bi-github me-1"></i> Tautan GitHub</label>
                                <input type="url" name="github" id="github"
                                       class="ds-input @error('github') is-invalid @enderror"
                                       value="{{ old('github', $mahasiswa->github) }}" placeholder="https://github.com/username">
                                @error('github')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="linkedin" class="ds-form-label"><i class="bi bi-linkedin me-1"></i> Tautan LinkedIn</label>
                                <input type="url" name="linkedin" id="linkedin"
                                       class="ds-input @error('linkedin') is-invalid @enderror"
                                       value="{{ old('linkedin', $mahasiswa->linkedin) }}" placeholder="https://linkedin.com/in/username">
                                @error('linkedin')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="instagram" class="ds-form-label"><i class="bi bi-instagram me-1"></i> Instagram</label>
                                <input type="text" name="instagram" id="instagram"
                                       class="ds-input @error('instagram') is-invalid @enderror"
                                       value="{{ old('instagram', $mahasiswa->instagram) }}" placeholder="@username">
                                @error('instagram')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="ds-divider mb-4"></div>

                        {{-- CV / Resume Upload --}}
                        <div class="mb-5">
                            <label for="cv_file" class="ds-form-label"><i class="bi bi-file-earmark-person me-1"></i> CV / Resume (PDF)</label>
                            
                            @if($mahasiswa->cv_file)
                                <div class="mb-3 p-3 ds-cv-current-box d-flex align-items-center justify-content-between">
                                    <span class="small text-muted"><i class="bi bi-file-pdf text-danger me-2"></i>CV Saat Ini Terunggah</span>
                                    <a href="{{ Storage::url($mahasiswa->cv_file) }}" target="_blank" class="ds-btn-ghost ds-btn-sm">
                                        <i class="bi bi-eye me-1"></i> Lihat CV
                                    </a>
                                </div>
                            @endif
                            
                            <input type="file" name="cv_file" id="cv_file"
                                   class="ds-input @error('cv_file') is-invalid @enderror" accept="application/pdf">
                            @error('cv_file')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                            <div class="ds-hint mt-2">Hanya format PDF (maks. 5 MB)</div>
                        </div>

                        <div class="d-flex justify-content-end align-items-center mt-5">
                            <button type="submit" class="ds-btn-primary px-4">
                                <i class="bi bi-check-lg me-1"></i> Simpan Profil
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
.ds-profile-edit {
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
.ds-completion-badge {
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
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
.ds-card-body {
    padding: 32px !important;
}

/* ── Avatar Upload Component ── */
.ds-avatar-upload-container {
    width: 120px;
    height: 120px;
    border-radius: 24px;
    border: 3px solid var(--surface-secondary);
    display: inline-block;
    overflow: hidden;
    background-color: var(--surface-secondary);
    box-shadow: var(--shadow-soft);
}
.ds-upload-avatar {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.ds-upload-avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 2.25rem;
    color: var(--color-primary);
}

/* ── Form Inputs ── */
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
.ds-input-readonly {
    width: 100%;
    border-radius: var(--radius-input);
    border: 1px solid var(--border-soft);
    background-color: var(--surface-secondary);
    color: var(--text-secondary);
    padding: 12px 16px;
    font-size: 0.9rem;
    outline: none;
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

/* ── CV current box ── */
.ds-cv-current-box {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    border-radius: var(--radius-input);
}

/* ── Hint & Alerts ── */
.ds-hint {
    font-size: 0.78rem;
    color: var(--text-secondary);
}
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
    padding: 8px 16px;
    font-size: 0.85rem;
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
    font-size: 0.8rem !important;
}
</style>
@endpush

@endsection
