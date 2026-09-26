<div class="mb-4 avoid-break">
    <div class="section-title">Prestasi</div>
    @if($mahasiswa->achievements->count() > 0)
        <table class="cert-table">
            <thead>
                <tr>
                    <th>Judul Prestasi</th>
                    <th style="width: 60px;">Tingkat</th>
                    <th style="width: 40px; text-align: center;">Tahun</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswa->achievements as $achievement)
                <tr>
                    <td>{{ $achievement->judul }}</td>
                    <td>{{ ucfirst($achievement->level) }}</td>
                    <td style="text-align: center;">{{ $achievement->tahun }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="font-size: 8px; color: #777; margin: 0;">Belum ada prestasi yang ditambahkan.</p>
    @endif
</div>
