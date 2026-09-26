<div class="section-content mb-3">
    <div class="section-title">Ringkasan Portfolio</div>
    <table class="stats-table">
        <tr>
            <td class="stats-label">Proyek Disetujui</td>
            <td class="stats-value">{{ $portfolioSummary['total_projects'] }}</td>
        </tr>
        <tr>
            <td class="stats-label">Keahlian</td>
            <td class="stats-value">{{ $portfolioSummary['total_skills'] }}</td>
        </tr>
        <tr>
            <td class="stats-label">Sertifikat</td>
            <td class="stats-value">{{ $portfolioSummary['total_certificates'] }}</td>
        </tr>
        <tr>
            <td class="stats-label">Prestasi</td>
            <td class="stats-value">{{ $portfolioSummary['total_achievements'] }}</td>
        </tr>
    </table>
</div>
