<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TourismSubmission;
use Illuminate\Auth\Access\HandlesAuthorization;

class TourismSubmissionPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TourismSubmission $tourismSubmission): bool
    {
        return $user->id === $tourismSubmission->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TourismSubmission $tourismSubmission): bool
    {
        return $user->id === $tourismSubmission->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TourismSubmission $tourismSubmission): bool
    {
        return $user->id === $tourismSubmission->user_id;
    }
}
