<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ListingService
{
    public function create(User $user, array $data): Listing
    {
        return $user->listings()->create([
            ...$data,
            'status' => 'pending',
            'published_at' => null,
        ]);
    }

    public function update(User $user, Listing $listing, array $data): Listing
    {
        $listing->update([
            ...$data,
            'status' => 'pending',
            'published_at' => null,
            'rejection_reason' => null,
        ]);

        return $listing;
    }

    public function approve(User $admin, Listing $listing): Listing
    {
        if ($listing->status !== 'pending') {
            throw ValidationException::withMessages([
                'listing' => 'Only pending listings can be approved.',
            ]);
        }

        $listing->update([
            'status' => 'approved',
            'published_at' => now(),
            'rejection_reason' => null,
        ]);

        return $listing;
    }

    public function reject(User $admin, Listing $listing, string $reason): Listing
    {
        if ($listing->status !== 'pending') {
            throw ValidationException::withMessages([
                'listing' => 'Only pending listings can be rejected.',
            ]);
        }

        $listing->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'published_at' => null,
        ]);

        return $listing;
    }
}
