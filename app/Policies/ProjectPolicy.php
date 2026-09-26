<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isMahasiswa();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->isAdmin() || ($user->isMahasiswa() && $user->mahasiswa->id === $project->mahasiswa_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isMahasiswa();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->isMahasiswa() 
            && $user->mahasiswa->id === $project->mahasiswa_id
            && in_array($project->status, ['draft', 'rejected']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->isMahasiswa() 
            && $user->mahasiswa->id === $project->mahasiswa_id
            && $project->status !== 'approved';
    }

    /**
     * Determine whether the user can submit the model for verification.
     */
    public function submit(User $user, Project $project): bool
    {
        return $user->isMahasiswa() 
            && $user->mahasiswa->id === $project->mahasiswa_id
            && in_array($project->status, ['draft', 'rejected']);
    }

    /**
     * Determine whether the user can request a revision.
     */
    public function requestRevision(User $user, Project $project): bool
    {
        return $user->isMahasiswa() 
            && $user->mahasiswa->id === $project->mahasiswa_id
            && $project->status === 'approved';
    }

    /**
     * Determine whether the user can request a deletion.
     */
    public function requestDelete(User $user, Project $project): bool
    {
        return $user->isMahasiswa() 
            && $user->mahasiswa->id === $project->mahasiswa_id
            && $project->status === 'approved';
    }
}
