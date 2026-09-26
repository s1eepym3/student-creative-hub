<?php

namespace App\Services;

use App\Models\MediaFile;
use App\Models\Project;
use App\Models\ProjectTechnology;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProjectService
{
    public function __construct(protected FileService $fileService) {}

    /**
     * Store a new project along with its thumbnail and technologies.
     */
    public function store(array $data, UploadedFile $thumbnail, array $technologies = []): Project
    {
        // Generate unique slug
        $slug = Str::slug($data['judul']);
        $originalSlug = $slug;
        $count = 1;
        while (Project::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $data['slug'] = $slug;

        // Upload thumbnail
        $data['thumbnail'] = $this->fileService->upload($thumbnail, 'projects/thumbnails');

        $project = Project::create($data);

        // Sync technologies
        $this->syncTechnologies($project, $technologies);

        return $project;
    }

    /**
     * Update an existing project. Does not change slug.
     */
    public function update(Project $project, array $data, ?UploadedFile $thumbnail, array $technologies = []): Project
    {
        if ($thumbnail) {
            $data['thumbnail'] = $this->fileService->replace(
                $project->thumbnail,
                $thumbnail,
                'projects/thumbnails'
            );
        }

        $project->update($data);

        $this->syncTechnologies($project, $technologies);

        return $project;
    }

    /**
     * Sync project technologies.
     */
    protected function syncTechnologies(Project $project, array $technologies): void
    {
        // Clear old ones
        $project->technologies()->delete();

        // Add new ones
        foreach ($technologies as $tech) {
            $project->technologies()->create([
                'technology_name' => trim($tech),
            ]);
        }
    }

    /**
     * Upload a new media file for a project.
     */
    public function uploadMedia(Project $project, UploadedFile $file): MediaFile
    {
        $path = $this->fileService->upload($file, 'projects/media');

        return $project->mediaFiles()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
        ]);
    }

    /**
     * Delete a media file.
     */
    public function deleteMedia(MediaFile $media): void
    {
        $this->fileService->delete($media->file_path);
        $media->delete();
    }

    /**
     * Delete a project entirely, including its files.
     */
    public function deleteProject(Project $project): void
    {
        // Delete all media files physically and records
        foreach ($project->mediaFiles as $media) {
            $this->deleteMedia($media);
        }

        // Delete thumbnail
        $this->fileService->delete($project->thumbnail);

        // Delete project (cascade should handle related records if DB set up, but technologies are deleted manually or cascade)
        $project->technologies()->delete();
        $project->delete();
    }
}
