# Listings + Moderation

A Laravel server-rendered application that lets providers create listings and admins moderate them, with a public searchable listing page.

## Stack

- **Framework:** Laravel (Blade templates)
- **CSS:** Tailwind CSS
- **JavaScript:** Vanilla JS (progressive enhancement)
- **Database:** SQLite / MySQL
- **Testing:** PHPUnit

## Setup Instructions

### 1. Clone & Install Dependencies

```bash
cd d:\installations\xampp\htdocs\listings-app
composer install
npm install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` if needed:
```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 3. Database Migration & Seeding

```bash
php artisan migrate
php artisan db:seed
```

This creates:
- 1 admin user
- 1 provider user  
- 15 seeded listings (6 approved, 4 pending, 2 rejected)

### 4. Build Assets

```bash
npm run dev
# or for production
npm run build
```

### 5. Run the Application

```bash
php artisan serve
```

Visit `http://localhost:8000`

---

## Seeded Credentials

### Admin Account
- **Email:** `admin@example.com`
- **Password:** `password`
- **Role:** admin

### Provider Account
- **Email:** `provider@example.com`
- **Password:** `password`
- **Role:** provider

---

## Features

### Public Pages
- **GET /listings** - Browse approved listings with search, filters, and sorting
  - Keyword search (title & description)
  - City filter (exact match)
  - Sort by: newest, price (low-high), price (high-low)
  - Pagination: 10 per page
  
- **GET /listings/{listing}** - View listing detail (only approved listings accessible)

### Provider Dashboard
- **GET /dashboard** - View all own listings (all statuses)
- **GET /listings/create** - Create new listing form
- **POST /listings** - Store new listing
- **GET /listings/{listing}/edit** - Edit own listing
- **PUT /listings/{listing}** - Update own listing
  - Editing approved/rejected listings resets status to pending

### Admin Moderation
- **GET /admin/listings** - View pending listings (newest first)
- **POST /admin/listings/{listing}/approve** - Approve listing
  - Sets status: approved
  - Sets published_at: now()
  - Clears rejection_reason
  
- **POST /admin/listings/{listing}/reject** - Reject listing
  - Sets status: rejected
  - Requires rejection_reason
  - Keeps published_at: null

---

## Business Rules (Service Layer)

All business logic is in `app/Services/ListingService`:

1. **Provider creates listing** → status = pending, published_at = null
2. **Provider edits approved/rejected listing** → automatically resets to pending
3. **Admin approves pending listing** → published_at = now(), rejection_reason = null
4. **Admin rejects listing** → requires reason, keeps published_at = null

### Authorization (Policies)

- **Provider:** Can only edit/view own listings
- **Admin:** Can view/approve/reject any listing
- Pending/rejected listings never visible publicly

---

## JavaScript Enhancements (Progressive)

Both enhancements work without JavaScript (forms submit normally).

### JS-1: Debounced Auto-Submit on Public Filters
- **Location:** `/listings` page
- Keyword search (`q`): 400ms debounce
- City filter: immediate submit
- Sort dropdown: immediate submit
- Fallback: visible "Search" button always works

### JS-2: Confirm Dialogs for Admin Moderation  
- **Location:** `/admin/listings` page
- Approve: `"Approve this listing?"` confirm
- Reject: `"Reject with this reason?"` confirm + validates reason is provided
- Fallback: forms submit normally without JS

---

## Tests

Run the 4 required tests:

```bash
php artisan test --filter ListingTest
```

### Tests Included

1. **Unit:** Service creates listing with status=pending & published_at=null ✓
2. **Unit:** Service approves listing, sets published_at & clears rejection_reason ✓
3. **Feature:** Public /listings shows only approved listings ✓
4. **Feature:** Provider cannot edit another user's listing (403) ✓

---

## Architecture Notes

### Why This Design?

**Service Layer (`ListingService`)**
- Single source of truth for all listing business rules
- Easy to test independently
- Reusable across controllers, commands, queues

**Form Requests (`StoreListingRequest`, `UpdateListingRequest`, `RejectListingRequest`)**
- Centralized validation
- Automatic 422 response on validation failure
- Move validation logic out of controllers

**Policies (`ListingPolicy`)**
- Clean authorization checks using Laravel's built-in policy system
- `authorize()` in controllers prevents unauthorized access early
- Consistent with Laravel conventions

**Thin Controllers**
- Receive request → authorize → call service → return response
- No business logic, no complex queries
- Easy to understand and test

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ListingController.php (public + provider CRUD)
│   │   └── Admin/ListingModerationController.php
│   └── Requests/
│       ├── StoreListingRequest.php
│       ├── UpdateListingRequest.php
│       └── RejectListingRequest.php
├── Models/
│   ├── Listing.php (with scopeApproved, user relationship)
│   └── User.php (with isAdmin, isProvider, listings relationship)
├── Policies/
│   └── ListingPolicy.php (update, moderate)
└── Services/
    └── ListingService.php (create, update, approve, reject)

resources/
├── views/
│   ├── listings/
│   │   ├── index.blade.php (public listing page with filters)
│   │   ├── create.blade.php (create form)
│   │   ├── edit.blade.php (edit form)
│   │   └── show.blade.php (detail page)
│   ├── admin/listings/
│   │   └── index.blade.php (moderation page)
│   └── dashboard.blade.php (provider dashboard listing own listings)
└── js/
    └── app.js (debounce + confirm dialogs)

tests/
└── Feature/
    └── ListingTest.php (4 required tests)
```

---

## Next Steps (1-2 Days)

If extending this project:

- [ ] Add image upload to listings (with storage + validation)
- [ ] Email notifications (admin notified on new listings, provider on decision)
- [ ] Search filter persistence in user preferences
- [ ] Categories/subcategories for listings
- [ ] Listing expiration logic
- [ ] User messaging/contact system
- [ ] Admin dashboard with stats & charts
- [ ] Export listings to CSV/PDF
- [ ] Rate limiting on listing creation

---

## Notes

- All timestamps use Laravel's default timestamp format
- Price stored in cents (integer) to avoid float precision issues
- Uses Tailwind CSS for clean, responsive UI
- No external dependencies for JavaScript (vanilla only)
- Database seeder creates realistic test data via factories
- All relationships use conventional foreign key naming
