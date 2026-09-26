<div class="section-content mb-3">
    <div class="section-title">Tentang Saya</div>
    <p class="about-text">
        {{ $mahasiswa->bio ?: 'Mahasiswa belum mengisi biografi diri.' }}
    </p>
    
    <div style="font-size: 9px;">
        <div class="contact-item">
            <span class="contact-label">Email</span>
            <span class="contact-value">{{ $mahasiswa->user->email }}</span>
        </div>
        @if($mahasiswa->github)
        <div class="contact-item">
            <span class="contact-label">GitHub</span>
            <span class="contact-value">
                <a href="{{ $mahasiswa->github }}">{{ str_replace(['https://', 'http://', 'www.'], '', $mahasiswa->github) }}</a>
            </span>
        </div>
        @endif
        @if($mahasiswa->linkedin)
        <div class="contact-item">
            <span class="contact-label">LinkedIn</span>
            <span class="contact-value">
                <a href="{{ $mahasiswa->linkedin }}">{{ str_replace(['https://', 'http://', 'www.'], '', $mahasiswa->linkedin) }}</a>
            </span>
        </div>
        @endif
        @if($mahasiswa->instagram)
        <div class="contact-item">
            <span class="contact-label">Instagram</span>
            <span class="contact-value">{{ '@' . str_replace(['https://instagram.com/', 'https://www.instagram.com/', 'instagram.com/', '@'], '', $mahasiswa->instagram) }}</span>
        </div>
        @endif
    </div>
</div>
