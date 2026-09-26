@extends('layouts.app')

@section('content')
<div class="ds-skills-form container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Header/Title --}}
            <div class="mb-4 text-center">
                <h2 class="ds-page-title">Edit Kemahiran Keahlian</h2>
                <p class="ds-page-subtitle">Sesuaikan tingkat kemahiran keahlian yang telah Anda kuasai.</p>
            </div>

            {{-- Card Container --}}
            <div class="ds-card">
                <div class="ds-card-body">
                    <form action="{{ route('mahasiswa.skills.update', $skill) }}" method="POST">
                        @csrf @method('PUT')
                        
                        <div class="mb-4">
                            <label class="ds-form-label">Keahlian</label>
                            <input type="text" class="ds-input-readonly" value="{{ $skill->nama_skill }} ({{ $skill->kategori }})" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="level" class="ds-form-label">Tingkat Kemahiran</label>
                            <select name="level" id="level"
                                    class="ds-select @error('level') is-invalid @enderror" required>
                                <option value="Beginner" {{ old('level', $pivot->pivot->level) == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="Intermediate" {{ old('level', $pivot->pivot->level) == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="Advanced" {{ old('level', $pivot->pivot->level) == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                            @error('level')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('mahasiswa.skills.index') }}" class="ds-btn-ghost">Batal</a>
                            <button type="submit" class="ds-btn-primary">Perbarui Tingkat</button>
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
.ds-skills-form {
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

@endsection
