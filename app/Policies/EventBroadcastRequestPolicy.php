<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EventBroadcastRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventBroadcastRequestPolicy
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
    public function view(User $user, EventBroadcastRequest $eventBroadcastRequest): bool
    {
        return $user->id === $eventBroadcastRequest->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EventBroadcastRequest $eventBroadcastRequest): bool
    {
        return $user->id === $eventBroadcastRequest->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventBroadcastRequest $eventBroadcastRequest): bool
    {
        return $user->id === $eventBroadcastRequest->user_id;
    }
}
