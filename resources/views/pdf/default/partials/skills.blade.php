<div class="section-content mb-3">
    <div class="section-title">Keahlian</div>
    @if($mahasiswa->skills->count() > 0)
        <div class="skills-list">
            @foreach($mahasiswa->skills as $skill)
                @php
                    $levelClass = '';
                    if ($skill->pivot->level === 'advanced') {
                        $levelClass = 'badge-success';
                    }
                @endphp
                <span class="badge {{ $levelClass }}">{{ $skill->nama_skill }} ({{ ucfirst($skill->pivot->level) }})</span>
            @endforeach
        </div>
    @else
        <p style="font-size: 9px; color: #777; margin: 0;">Belum ada keahlian yang ditambahkan.</p>
    @endif
</div>
