<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Listing;

class ListingPolicy
{
    public function update(User $user, Listing $listing)
    {
        return $user->id === $listing->user_id;
    }

    public function moderate(User $user)
    {
        return $user->isAdmin();
    }
}