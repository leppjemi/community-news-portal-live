<?php

namespace App\Policies;

use App\Models\NewsPost;
use App\Models\User;

class NewsPostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Anyone can view published posts
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NewsPost $newsPost): bool
    {
        // Users can view their own posts, editors/admins can view all, guests can view published
        return $newsPost->isPublished()
            || $user->id === $newsPost->user_id
            || $user->isEditor();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('user') || $user->isEditor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NewsPost $newsPost): bool
    {
        // Users can only edit their own posts, editors/admins can edit all
        return $user->id === $newsPost->user_id || $user->isEditor();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NewsPost $newsPost): bool
    {
        // Users can only delete their own posts, editors/admins can delete all
        return $user->id === $newsPost->user_id || $user->isEditor();
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, NewsPost $newsPost): bool
    {
        return $user->isEditor();
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, NewsPost $newsPost): bool
    {
        return $user->isEditor();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, NewsPost $newsPost): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, NewsPost $newsPost): bool
    {
        return false;
    }
}
