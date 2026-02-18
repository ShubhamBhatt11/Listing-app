<?php

namespace Tests\Unit;

use App\Models\Listing;
use App\Models\User;
use App\Services\ListingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_listing_sets_status_pending_and_published_at_null(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);
        $service = app(ListingService::class);

        $listing = $service->create($provider, [
            'title' => 'Unit Test Listing',
            'description' => 'A description for unit test',
            'city' => 'London',
            'price_cents' => 12345,
        ]);

        $this->assertSame('pending', $listing->status);
        $this->assertNull($listing->published_at);
    }

    public function test_approve_pending_sets_published_at_and_clears_rejection_reason(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $listing = Listing::factory()->create([
            'status' => 'pending',
            'published_at' => null,
            'rejection_reason' => 'Old reason',
        ]);

        $service = app(ListingService::class);
        $service->approve($admin, $listing);

        $listing->refresh();

        $this->assertSame('approved', $listing->status);
        $this->assertNotNull($listing->published_at);
        $this->assertNull($listing->rejection_reason);
    }
}
