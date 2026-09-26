@extends('layouts.app')

@section('content')
<div class="ds-admin-categories container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="ds-page-title">Manajemen Kategori</h2>
                    <p class="ds-page-subtitle">Kelola kategori proyek yang tersedia untuk mahasiswa.</p>
                </div>
                <a href="{{ route('admin.categories.create') }}" class="ds-btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
                </a>
            </div>

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="ds-alert ds-alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="ds-alert ds-alert-danger mb-4"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}</div>
            @endif

            {{-- Table Card --}}
            @if($categories->count() > 0)
            <div class="ds-card">
                <div class="ds-table-wrapper">
                    <table class="ds-table">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 60px;">#</th>
                                <th>Nama Kategori</th>
                                <th>Dibuat</th>
                                <th class="text-end pe-4" style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                            <tr>
                                <td class="ps-4">{{ $categories->firstItem() + $index }}</td>
                                <td>
                                    <span class="fw-semibold" style="color: var(--text-primary);">{{ $category->nama_kategori }}</span>
                                </td>
                                <td style="color: var(--text-secondary);">{{ $category->created_at->format('d M Y') }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="ds-btn-ghost ds-btn-sm">Edit</a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ds-btn-ghost ds-btn-sm text-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($categories->hasPages())
                <div class="px-4 py-3 border-top" style="border-color: var(--border-soft) !important;">
                    {{ $categories->links() }}
                </div>
                @endif
            </div>
            @else
            <div class="ds-card">
                <div class="text-center py-5 px-4">
                    <i class="bi bi-folder-plus d-block mb-3" style="font-size: 2.5rem; opacity: 0.25; color: var(--text-secondary);"></i>
                    <p class="mb-3" style="color: var(--text-secondary);">Belum ada kategori. Klik tombol di atas untuk membuat kategori pertama.</p>
                    <a href="{{ route('admin.categories.create') }}" class="ds-btn-primary ds-btn-sm">Tambah Kategori</a>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

@push('styles')
<style>
.ds-admin-categories { font-family: var(--font-body); }
.ds-page-title { font-family: var(--font-heading); font-weight: 600; font-size: 1.5rem; color: var(--text-primary); margin: 0; }
.ds-page-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin: 4px 0 0; }
.ds-card { background-color: var(--surface-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-card); box-shadow: var(--shadow-soft); overflow: hidden; }
.ds-table-wrapper { overflow-x: auto; }
.ds-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
.ds-table thead tr { background-color: var(--surface-secondary); }
.ds-table th { font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); padding: 14px 16px; border-bottom: 1px solid var(--border-soft); }
.ds-table td { padding: 14px 16px; border-bottom: 1px solid var(--border-soft); vertical-align: middle; }
.ds-table tbody tr:last-child td { border-bottom: none; }
.ds-table tbody tr:hover { background-color: var(--surface-hover); }
.ds-alert { padding: 14px 20px; border-radius: var(--radius-button); font-size: 0.875rem; }
.ds-alert-success { background-color: #eef6f0; color: #2e5e3a; border: 1px solid rgba(46, 125, 50, 0.15); }
.ds-alert-danger { background-color: #fdf0ef; color: #8b2020; border: 1px solid rgba(229, 115, 115, 0.15); }
.ds-btn-primary { display: inline-flex; align-items: center; justify-content: center; background-color: var(--color-primary); color: #fff; border: none; border-radius: var(--radius-button); padding: 10px 20px; font-size: 0.875rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: background-color 150ms ease-out, transform 150ms ease-out; }
.ds-btn-primary:hover { background-color: #4a6459; transform: translateY(-1px); color: #fff; }
.ds-btn-ghost { display: inline-flex; align-items: center; justify-content: center; background-color: transparent; color: var(--text-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-button); padding: 8px 16px; font-size: 0.82rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 150ms ease-out; }
.ds-btn-ghost:hover { background-color: var(--surface-hover); border-color: var(--color-primary); color: var(--color-primary); }
.ds-btn-sm { padding: 6px 14px !important; font-size: 0.78rem !important; }
</style>
@endpush

@endsection