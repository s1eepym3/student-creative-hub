<div class="mb-4">
    <div class="section-title">Proyek Unggulan</div>
    @if($featuredProjects->count() > 0)
        @foreach($featuredProjects as $project)
        <div class="project-card">
            <div class="project-category">{{ $project->category->nama_kategori ?? 'PROJECT' }}</div>
            <div class="project-title">{{ $project->judul }}</div>
            
            <div class="project-description">
                {{ $project->deskripsi }}
            </div>

            <!-- Inline Technologies -->
            @if($project->technologies->count() > 0)
            <div class="project-tech">
                {{ $project->technologies->pluck('technology_name')->join(' • ') }}
            </div>
            @endif

            <!-- Links -->
            <div class="project-links">
                @if($project->github_url)
                    <a href="{{ $project->github_url }}">Repository</a>
                @endif
                @if($project->project_url)
                    <a href="{{ $project->project_url }}">Demo</a>
                @endif
            </div>
        </div>
        @endforeach
    @else
        <p style="font-size: 9px; color: #777; margin: 0;">Belum ada proyek yang disetujui.</p>
    @endif
</div>
