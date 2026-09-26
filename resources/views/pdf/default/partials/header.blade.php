<table class="header-table">
    <tr>
        <!-- Student Avatar & Name -->
        <td valign="top" style="width: 85%;">
            <div style="display: table; width: 100%;">
                <!-- Avatar -->
                <div style="display: table-cell; width: 75px; padding-right: 12px; vertical-align: top;">
                    @if($mahasiswa->foto_profil && file_exists(public_path('storage/' . $mahasiswa->foto_profil)))
                        <img src="{{ public_path('storage/' . $mahasiswa->foto_profil) }}" style="width: 65px; height: 65px; object-fit: cover; border-radius: 4px; border: 2px solid #198754;">
                    @else
                        <div style="width: 65px; height: 65px; background-color: #e8f5e9; border: 2px solid #198754; border-radius: 4px; text-align: center; line-height: 65px; color: #198754; font-size: 24px; font-weight: bold;">
                            {{ strtoupper(substr($mahasiswa->nama_lengkap, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Name & University -->
                <div style="display: table-cell; vertical-align: top;">
                    <h1 class="student-name">{{ $mahasiswa->nama_lengkap }}</h1>
                    <p class="student-meta">
                        {{ $mahasiswa->prodi ?? 'Mahasiswa' }} • Universitas Malikussaleh
                    </p>
                    <p class="student-meta" style="margin-top: 2px;">
                        NIM {{ $mahasiswa->nim }} • Angkatan {{ $mahasiswa->angkatan }}
                    </p>
                </div>
            </div>
        </td>

        <!-- Portfolio Score Badge (Right Side) -->
        <td valign="top" text-align="right" style="width: 15%; text-align: right;">
            <div class="score-badge">
                <span class="score-label">Score</span>
                <span class="score-value">{{ $score }}%</span>
                <span class="score-text">{{ $scoreText }}</span>
            </div>
        </td>
    </tr>
</table>
