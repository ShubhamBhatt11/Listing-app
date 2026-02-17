<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Http\Requests\RejectListingRequest;
use App\Services\ListingService;

class ListingModerationController extends Controller
{
    protected $service;

    public function __construct(ListingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // Authorize against the Listing class policy ability (policy method only needs the current user)
        $this->authorize('moderate', \App\Models\Listing::class);

        $listings = Listing::where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.listings.index', compact('listings'));
    }

    public function approve(Listing $listing)
    {
        $this->authorize('moderate', \App\Models\Listing::class);

        $this->service->approve(auth()->user(), $listing);

        return back()->with('success', 'Listing approved successfully!');
    }

    public function reject(RejectListingRequest $request, Listing $listing)
    {
        $this->authorize('moderate', \App\Models\Listing::class);

        $this->service->reject(auth()->user(), $listing, $request->rejection_reason);

        return back()->with('success', 'Listing rejected successfully!');
    }
}
