<div class="qr-section">
    <p class="qr-title">Scan to view the interactive online version of this portfolio</p>

    @if($mahasiswa->qrPortfolio && file_exists(public_path('storage/' . $mahasiswa->qrPortfolio->qr_path)))
        <img src="{{ public_path('storage/' . $mahasiswa->qrPortfolio->qr_path) }}"
             style="width: 90px; height: 90px; border: 1px solid #e9ecef; padding: 2px; border-radius: 3px;"
             alt="QR Portfolio">
    @else
        <div style="width: 90px; height: 90px; margin: 0 auto; background-color: #e9ecef; border: 1px dashed #adb5bd; border-radius: 4px; text-align: center; line-height: 90px;">
            <span style="font-size: 8px; color: #6c757d;">QR Belum<br>Dibuat</span>
        </div>
    @endif

    <p class="qr-url">{{ url('/p/' . $mahasiswa->slug) }}</p>
</div>
