<?php

namespace App\Policies;

use App\Models\User;

class ReviewPolicy
{
    public function create(User $authUser, int $userId): bool
    {
        return $authUser->id == $userId;
    }
}
