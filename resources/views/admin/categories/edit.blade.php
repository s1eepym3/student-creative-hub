@extends('layouts.app')

@section('content')
<div class="ds-admin-categories container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            {{-- Header --}}
            <div class="mb-4">
                <h2 class="ds-page-title">Edit Kategori</h2>
                <p class="ds-page-subtitle">Perbarui nama kategori proyek.</p>
            </div>

            {{-- Form Card --}}
            <div class="ds-card">
                <div class="ds-card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-4">
                            <label for="nama_kategori" class="ds-form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" id="nama_kategori"
                                   class="ds-input @error('nama_kategori') is-invalid @enderror"
                                   value="{{ old('nama_kategori', $category->nama_kategori) }}" required>
                            @error('nama_kategori')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.categories.index') }}" class="ds-btn-ghost">Batal</a>
                            <button type="submit" class="ds-btn-primary">Perbarui Kategori</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.ds-admin-categories { font-family: var(--font-body); }
.ds-page-title { font-family: var(--font-heading); font-weight: 600; font-size: 1.5rem; color: var(--text-primary); margin: 0; }
.ds-page-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin: 4px 0 0; }
.ds-card { background-color: var(--surface-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-card); box-shadow: var(--shadow-soft); overflow: hidden; }
.ds-card-body { padding: 32px !important; }
.ds-form-label { font-size: 0.875rem; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; display: block; }
.ds-input { width: 100%; border-radius: var(--radius-input); border: 1px solid var(--border-soft); background-color: var(--surface-primary); color: var(--text-primary); padding: 12px 16px; font-size: 0.9rem; outline: none; transition: border-color 150ms ease-out, box-shadow 150ms ease-out; }
.ds-input:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(92, 124, 111, 0.15); }
.ds-btn-primary { display: inline-flex; align-items: center; background-color: var(--color-primary); color: #fff; border: none; border-radius: var(--radius-button); padding: 12px 24px; font-size: 0.9rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: background-color 150ms ease-out; }
.ds-btn-primary:hover { background-color: #4a6459; color: #fff; }
.ds-btn-ghost { display: inline-flex; align-items: center; background-color: transparent; color: var(--text-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-button); padding: 12px 24px; font-size: 0.9rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 150ms ease-out; }
.ds-btn-ghost:hover { background-color: var(--surface-hover); border-color: var(--color-primary); color: var(--color-primary); }
</style>
@endpush

@endsection
