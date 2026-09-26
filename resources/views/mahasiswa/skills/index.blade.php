@extends('layouts.app')

@section('content')
<div class="ds-skills container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            {{-- Header/Title --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="ds-page-title">Keahlian Saya</h2>
                    <p class="ds-page-subtitle">Kelola dan tunjukkan keahlian (skills) Anda di profil publik.</p>
                </div>
                <a href="{{ route('mahasiswa.skills.create') }}" class="ds-btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Keahlian
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
                    @if($mahasiswaSkills->count() > 0)
                        <div class="table-responsive">
                            <table class="table ds-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th>Keahlian</th>
                                        <th>Kategori</th>
                                        <th>Tingkat Kemahiran</th>
                                        <th style="width: 180px; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mahasiswaSkills as $index => $skill)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $skill->nama_skill }}</strong></td>
                                        <td><span class="ds-badge-category">{{ $skill->kategori }}</span></td>
                                        <td>
                                            @php
                                                $levelColors = [
                                                    'Beginner'     => ['color' => '#6B6B6B', 'bg' => '#F0EDEA'],
                                                    'Intermediate' => ['color' => '#C48A5A', 'bg' => '#FFF8F0'],
                                                    'Advanced'     => ['color' => '#2E7D32', 'bg' => '#E8F5E9']
                                                ];
                                                $lc = $levelColors[$skill->pivot->level] ?? ['color' => '#2E2E2E', 'bg' => '#F8F5F1'];
                                            @endphp
                                            <span class="ds-badge-level" style="color: {{ $lc['color'] }}; background-color: {{ $lc['bg'] }};">
                                                {{ $skill->pivot->level }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('mahasiswa.skills.edit', $skill) }}" class="ds-icon-btn" title="Edit Level">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('mahasiswa.skills.destroy', $skill) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus keahlian ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="ds-icon-btn ds-icon-btn-danger" title="Hapus Keahlian">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="ds-empty-state text-center py-5">
                            <i class="bi bi-lightning-charge ds-empty-icon mb-3"></i>
                            <h5 class="ds-empty-title">Keahlian Masih Kosong</h5>
                            <p class="ds-empty-desc mb-4">Tambahkan keahlian Anda untuk mempermudah pencarian oleh kurator atau pihak luar.</p>
                            <a href="{{ route('mahasiswa.skills.create') }}" class="ds-btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Keahlian Pertama
                            </a>
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
.ds-skills {
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
    min-width: 600px;
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

/* ── Badges ── */
.ds-badge-category {
    background-color: var(--surface-secondary);
    border: 1px solid var(--border-soft);
    color: var(--text-primary);
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}
.ds-badge-level {
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-block;
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
</style>
@endpush

@endsection
