@extends('layouts.app')

@section('content')
<div class="ds-admin-users container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="ds-alert ds-alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="ds-alert ds-alert-danger mb-4"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}</div>
            @endif

            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="ds-page-title">Manajemen Pengguna</h2>
                    <p class="ds-page-subtitle">Kelola dan verifikasi akun pendaftaran mahasiswa.</p>
                </div>
            </div>

            {{-- Filter Tabs + Table Card --}}
            <div class="ds-card">
                <div class="ds-tabs-wrapper">
                    <a class="ds-tab-link {{ $status === 'all' ? 'active' : '' }}" href="{{ route('admin.users.index', ['status' => 'all']) }}">
                        <i class="bi bi-people me-1"></i>Semua
                    </a>
                    <a class="ds-tab-link {{ $status === 'pending' ? 'active' : '' }}" href="{{ route('admin.users.index', ['status' => 'pending']) }}">
                        <i class="bi bi-person-exclamation me-1"></i>Menunggu
                    </a>
                    <a class="ds-tab-link {{ $status === 'active' ? 'active' : '' }}" href="{{ route('admin.users.index', ['status' => 'active']) }}">
                        <i class="bi bi-person-check me-1"></i>Aktif
                    </a>
                    <a class="ds-tab-link {{ $status === 'suspended' ? 'active' : '' }}" href="{{ route('admin.users.index', ['status' => 'suspended']) }}">
                        <i class="bi bi-person-slash me-1"></i>Ditangguhkan
                    </a>
                </div>

                <div class="ds-table-wrapper">
                    <table class="ds-table">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 25%">Nama Lengkap</th>
                                <th style="width: 12%">NIM</th>
                                <th style="width: 20%">Email</th>
                                <th style="width: 13%">Status</th>
                                <th style="width: 15%">Terdaftar</th>
                                <th class="pe-4 text-end" style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if($user->mahasiswa && $user->mahasiswa->foto_profil)
                                                <img src="{{ asset('storage/' . $user->mahasiswa->foto_profil) }}" alt="Avatar" class="ds-avatar me-3">
                                            @else
                                                <div class="ds-avatar-placeholder me-3">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold" style="color: var(--text-primary);">{{ $user->name }}</div>
                                                @if($user->mahasiswa && $user->mahasiswa->prodi !== '-')
                                                    <small style="color: var(--text-secondary);">{{ $user->mahasiswa->prodi }} ({{ $user->mahasiswa->angkatan }})</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code style="background-color: var(--surface-secondary); padding: 3px 8px; border-radius: 6px; font-size: 0.82rem; color: var(--text-primary);">{{ $user->mahasiswa ? $user->mahasiswa->nim : '-' }}</code>
                                    </td>
                                    <td style="color: var(--text-secondary);">{{ $user->email }}</td>
                                    <td>
                                        @if($user->status === 'active')
                                            <span class="ds-status-badge ds-status-active">Aktif</span>
                                        @elseif($user->status === 'inactive')
                                            <span class="ds-status-badge ds-status-pending">Menunggu</span>
                                        @else
                                            <span class="ds-status-badge ds-status-suspended">Ditangguhkan</span>
                                        @endif
                                    </td>
                                    <td style="color: var(--text-secondary); font-size: 0.82rem;">
                                        {{ $user->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="dropdown">
                                            <button class="ds-btn-ghost ds-btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Kelola
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end ds-dropdown-menu">
                                                @if($user->status === 'inactive')
                                                    <li>
                                                        <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success"><i class="bi bi-person-check me-2"></i>Setujui Akun</button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if($user->status === 'active')
                                                    <li>
                                                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-warning" onclick="return confirm('Apakah Anda yakin ingin menangguhkan akun ini?')"><i class="bi bi-person-dash me-2"></i>Tangguhkan Akun</button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if($user->status === 'suspended')
                                                    <li>
                                                        <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success"><i class="bi bi-person-check me-2"></i>Aktifkan Kembali</button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('PENTING: Menghapus akun akan menghapus seluruh file CV, foto profil, sertifikat, dan proyek mahasiswa tersebut secara permanen. Apakah Anda yakin?')">
                                                            <i class="bi bi-trash me-2"></i>Hapus Permanen
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5" style="color: var(--text-secondary);">
                                        <i class="bi bi-people d-block mb-2" style="font-size: 2.5rem; opacity: 0.25;"></i>
                                        Tidak ada data pengguna mahasiswa untuk filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-4 py-3 border-top" style="border-color: var(--border-soft) !important;">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
.ds-admin-users { font-family: var(--font-body); }
.ds-page-title { font-family: var(--font-heading); font-weight: 600; font-size: 1.5rem; color: var(--text-primary); margin: 0; }
.ds-page-subtitle { font-size: 0.875rem; color: var(--text-secondary); margin: 4px 0 0; }

/* Card */
.ds-card { background-color: var(--surface-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-card); box-shadow: var(--shadow-soft); overflow: hidden; }

/* Filter Tabs */
.ds-tabs-wrapper { display: flex; gap: 4px; padding: 12px 16px; border-bottom: 1px solid var(--border-soft); background-color: var(--surface-secondary); flex-wrap: wrap; }
.ds-tab-link { display: inline-flex; align-items: center; padding: 8px 16px; font-size: 0.82rem; font-weight: 500; color: var(--text-secondary); border-radius: 10px; text-decoration: none; transition: all 150ms; }
.ds-tab-link.active { background-color: var(--color-primary); color: #fff; }
.ds-tab-link:hover:not(.active) { background-color: var(--surface-hover); color: var(--text-primary); }

/* Table */
.ds-table-wrapper { overflow-x: auto; }
.ds-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
.ds-table thead tr { background-color: var(--surface-secondary); }
.ds-table th { font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-secondary); padding: 14px 16px; border-bottom: 1px solid var(--border-soft); }
.ds-table td { padding: 14px 16px; border-bottom: 1px solid var(--border-soft); vertical-align: middle; }
.ds-table tbody tr:last-child td { border-bottom: none; }
.ds-table tbody tr:hover { background-color: var(--surface-hover); }

/* Avatar */
.ds-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-soft); }
.ds-avatar-placeholder { width: 40px; height: 40px; border-radius: 50%; background-color: var(--color-primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; flex-shrink: 0; }

/* Status Badges */
.ds-status-badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
.ds-status-active { background-color: #eef6f0; color: #2e7d32; border: 1px solid rgba(46, 125, 50, 0.15); }
.ds-status-pending { background-color: #fdf6e8; color: #b57a1e; border: 1px solid rgba(181, 122, 30, 0.15); }
.ds-status-suspended { background-color: #fdf0ef; color: #c0392b; border: 1px solid rgba(192, 57, 43, 0.15); }

/* Dropdown */
.ds-dropdown-menu { border: 1px solid var(--border-soft); border-radius: var(--radius-button); box-shadow: var(--shadow-soft); }

/* Alerts */
.ds-alert { padding: 14px 20px; border-radius: var(--radius-button); font-size: 0.875rem; }
.ds-alert-success { background-color: #eef6f0; color: #2e5e3a; border: 1px solid rgba(46, 125, 50, 0.15); }
.ds-alert-danger { background-color: #fdf0ef; color: #8b2020; border: 1px solid rgba(229, 115, 115, 0.15); }

/* Buttons */
.ds-btn-ghost { display: inline-flex; align-items: center; justify-content: center; background-color: transparent; color: var(--text-primary); border: 1px solid var(--border-soft); border-radius: var(--radius-button); padding: 8px 16px; font-size: 0.82rem; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 150ms ease-out; }
.ds-btn-ghost:hover { background-color: var(--surface-hover); border-color: var(--color-primary); color: var(--color-primary); }
.ds-btn-sm { padding: 6px 14px !important; font-size: 0.78rem !important; }
</style>
@endpush

@endsection
