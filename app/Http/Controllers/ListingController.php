<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Services\ListingService;

class ListingController extends Controller
{
    protected $service;

    public function __construct(ListingService $service)
    {
        $this->service = $service;
    }

    // Public listing pages
    public function index()
    {
        $query = Listing::approved();

        if ($q = request('q')) {
            $query->where(function ($x) use ($q) {
                $x->where('title', 'like', "%$q%")
                  ->orWhere('description', 'like', "%$q%");
            });
        }

        if ($city = request('city')) {
            $query->where('city', $city);
        }

        $sort = request('sort', 'newest');

        if ($sort === 'price_asc') {
            $query->orderBy('price_cents');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price_cents');
        } else {
            $query->latest();
        }

        $listings = $query->paginate(10)->withQueryString();

        return view('listings.index', compact('listings'));
    }

    public function show(Listing $listing)
    {
        abort_if($listing->status !== 'approved', 404);

        return view('listings.show', compact('listing'));
    }

    // Provider CRUD
    public function create()
    {
        return view('listings.create');
    }

    public function store(StoreListingRequest $request)
    {
        $this->authorize('create', Listing::class);

        $this->service->create(auth()->user(), $request->validated());

        return redirect()->route('dashboard')->with('success', 'Listing created successfully!');
    }

    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);

        return view('listings.edit', compact('listing'));
    }

    public function update(UpdateListingRequest $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        $this->service->update(auth()->user(), $listing, $request->validated());

        return redirect()->route('dashboard')->with('success', 'Listing updated successfully!');
    }
}
