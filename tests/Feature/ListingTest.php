<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use App\Services\ListingService;

class ListingTest extends TestCase
{
    /**
     * Test 1: Unit - create listing sets status pending and published_at null
     */
    public function test_service_create_listing_sets_status_pending(): void
    {
        $user = User::factory()->create(['role' => 'provider']);
        $service = new ListingService();

        $listing = $service->create($user, [
            'title' => 'Test Listing',
            'description' => 'Test Description',
            'city' => 'New York',
            'price_cents' => 50000,
        ]);

        $this->assertEquals('pending', $listing->status);
        $this->assertNull($listing->published_at);
        $this->assertEquals($user->id, $listing->user_id);
    }

    /**
     * Test 2: Unit - approve pending listing sets published_at and clears rejection_reason
     */
    public function test_service_approve_listing_sets_published_at(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $listing = Listing::factory()->create([
            'status' => 'pending',
            'published_at' => null,
            'rejection_reason' => 'Previously rejected',
        ]);

        $service = new ListingService();
        $service->approve($admin, $listing);

        $listing->refresh();
        $this->assertEquals('approved', $listing->status);
        $this->assertNotNull($listing->published_at);
        $this->assertNull($listing->rejection_reason);
    }

    /**
     * Test 3: Feature - public /listings shows only approved listings
     */
    public function test_public_listings_page_shows_only_approved(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);
        
        // Create listings with different statuses
        $approved = Listing::factory()->create([
            'user_id' => $provider->id,
            'status' => 'approved',
            'published_at' => now(),
        ]);
        
        $pending = Listing::factory()->create([
            'user_id' => $provider->id,
            'status' => 'pending',
            'published_at' => null,
        ]);
        
        $rejected = Listing::factory()->create([
            'user_id' => $provider->id,
            'status' => 'rejected',
            'published_at' => null,
        ]);

        $response = $this->get('/listings');
        $response->assertStatus(200);
        $response->assertSee($approved->title);
        $response->assertDontSee($pending->title);
        $response->assertDontSee($rejected->title);
    }

    /**
     * Test 4: Feature - provider cannot edit another user's listing (403)
     */
    public function test_provider_cannot_edit_another_users_listing(): void
    {
        $provider1 = User::factory()->create(['role' => 'provider']);
        $provider2 = User::factory()->create(['role' => 'provider']);
        
        $listing = Listing::factory()->create([
            'user_id' => $provider1->id,
            'status' => 'pending',
        ]);

        // Try to edit another user's listing
        $response = $this->actingAs($provider2)
            ->put("/listings/{$listing->id}", [
                'title' => 'Hacked Title',
                'description' => 'Hacked Description',
                'city' => 'Hacked City',
                'price_cents' => 99999,
            ]);

        $response->assertForbidden();
        
        // Verify the listing wasn't updated
        $listing->refresh();
        $this->assertNotEquals('Hacked Title', $listing->title);
    }
}
