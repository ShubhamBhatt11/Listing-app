<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ListingService
{
    public function getProviderDashboardListings(User $provider): Collection
    {
        return $provider->listings()->latest()->get();
    }

    public function getPublicListings(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Listing::approved();

        $q = $filters['q'] ?? null;
        if ($q) {
            $query->where(function ($x) use ($q) {
                $x->where('title', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%");
            });
        }

        $city = $filters['city'] ?? null;
        if ($city) {
            $query->where('city', $city);
        }

        $sort = $filters['sort'] ?? 'newest';
        if ($sort === 'price_asc') {
            $query->orderBy('price_cents');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price_cents');
        } else {
            $query->latest();
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getPendingListingsForModeration(int $perPage = 10): LengthAwarePaginator
    {
        return Listing::where('status', 'pending')
            ->latest()
            ->paginate($perPage);
    }

    public function getAdminDashboardData(): array
    {
        return [
            'approvedCount' => Listing::where('status', 'approved')->count(),
            'pendingCount' => Listing::where('status', 'pending')->count(),
            'usersCount' => User::count(),
            'pendingListings' => Listing::where('status', 'pending')
                ->latest()
                ->with('user')
                ->take(5)
                ->get(),
        ];
    }

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
