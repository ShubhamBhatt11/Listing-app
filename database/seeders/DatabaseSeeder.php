<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Listing;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $provider = User::create([
            'name' => 'Provider',
            'email' => 'provider@example.com',
            'password' => Hash::make('password'),
            'role' => 'provider',
        ]);

        Listing::factory()->count(6)->create([
            'user_id' => $provider->id,
            'status' => 'approved',
            'published_at' => now(),
            'rejection_reason' => null,
        ]);

        Listing::factory()->count(4)->create([
            'user_id' => $provider->id,
            'status' => 'pending',
            'published_at' => null,
            'rejection_reason' => null,
        ]);

        Listing::factory()->count(2)->create([
            'user_id' => $provider->id,
            'status' => 'rejected',
            'published_at' => null,
            'rejection_reason' => 'Not acceptable',
        ]);

        Listing::factory()->count(3)->create([
            'user_id' => $provider->id,
            'status' => 'approved',
            'published_at' => now(),
            'rejection_reason' => null,
        ]);
    }
}
