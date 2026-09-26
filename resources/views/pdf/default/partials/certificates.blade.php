<div class="mb-4 avoid-break">
    <div class="section-title">Sertifikat</div>
    @if($mahasiswa->certificates->count() > 0)
        <table class="cert-table">
            <thead>
                <tr>
                    <th>Nama Kegiatan</th>
                    <th>Penyelenggara</th>
                    <th style="width: 40px; text-align: center;">Tahun</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswa->certificates as $cert)
                <tr>
                    <td>{{ $cert->nama_kegiatan }}</td>
                    <td>{{ $cert->penyelenggara }}</td>
                    <td style="text-align: center;">{{ $cert->tahun }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="font-size: 8px; color: #777; margin: 0;">Belum ada sertifikat yang ditambahkan.</p>
    @endif
</div>
