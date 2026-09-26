<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Upload a file to the given directory and return the path.
     */
    public function upload(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }

    /**
     * Delete a file from storage if it exists.
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Replace an old file with a new one. Deletes old, uploads new.
     */
    public function replace(?string $oldPath, UploadedFile $newFile, string $directory): string
    {
        $this->delete($oldPath);
        return $this->upload($newFile, $directory);
    }
}
