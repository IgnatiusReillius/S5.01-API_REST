<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin ?? false;
    }

    public function create(User $authUser, int $userId): bool
    {
        return $authUser->id == $userId;
    }

    public function viewUser(User $authUser, int $userId): bool
    {
        return $authUser->is_admin || $authUser->id == $userId;
    }

    public function view(User $authUser, Review $review): bool
    {
        return $authUser->is_admin || $authUser->id == $review->id_user;
    }

    public function viewBook(User $user): bool
    {
        return $user->is_admin ?? false;
    }
    
    public function update(User $authUser, Review $review): bool
    {
        return $authUser->is_admin || $authUser->id == $review->id_user;
    }

    public function delete(User $authUser, Review $review): bool
    {
        return $authUser->is_admin || $authUser->id == $review->id_user;
    }
    
    public function deleteUserReviews(User $authUser, int $userId): bool
    {
        return $authUser->is_admin || $authUser->id == $userId;
    }
}
