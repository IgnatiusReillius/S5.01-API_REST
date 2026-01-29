<?php

namespace App\Policies;

use App\Models\User;

class ReviewPolicy
{
    public function create(User $authUser, int $userId): bool
    {
        return $authUser->id == $userId;
    }
    
    public function viewUser(User $authUser, int $userId): bool
    {
        return $authUser->is_admin || $authUser->id == $userId;
    }
}
