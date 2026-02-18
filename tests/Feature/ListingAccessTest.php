<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListingAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_listings_shows_only_approved(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);

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

        $response->assertOk();
        $response->assertSee($approved->title);
        $response->assertDontSee($pending->title);
        $response->assertDontSee($rejected->title);
    }

    public function test_provider_cannot_edit_another_users_listing(): void
    {
        $owner = User::factory()->create(['role' => 'provider']);
        $otherProvider = User::factory()->create(['role' => 'provider']);

        $listing = Listing::factory()->create([
            'user_id' => $owner->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($otherProvider)->put("/listings/{$listing->id}", [
            'title' => 'Unauthorized edit',
            'description' => 'Trying to edit another user listing',
            'city' => 'Paris',
            'price_cents' => 20000,
        ]);

        $response->assertForbidden();
    }
}
