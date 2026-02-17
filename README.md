# Listings + Moderation

A Laravel server-rendered application that lets providers create listings and admins moderate them, with a public searchable listing page.

## Stack

- **Framework:** Laravel (Blade templates)
- **CSS:** Tailwind CSS
- **JavaScript:** Vanilla JS (progressive enhancement)
- **Database:** SQLite / MySQL
- **Testing:** PHPUnit

## Setup Instructions

### 1. Install Dependencies

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

### 3. Database Migration & Seeding

```bash
php artisan migrate
php artisan db:seed
```

### 4. Build Assets

```bash
npm run dev
```

### 5. Run Application

```bash
php artisan serve
```

---

## Seeded Credentials

### Admin Account
- **Email:** `admin@example.com`
- **Password:** `password`

### Provider Account
- **Email:** `provider@example.com`
- **Password:** `password`

---

## Features

### Public Pages
- **GET /listings** - Browse approved listings with search, filters, sorting (10 per page)
- **GET /listings/{listing}** - View listing detail (only approved)

### Provider Dashboard
- **GET /dashboard** - View own listings (all statuses)
- **GET /listings/create** - Create form
- **POST /listings** - Store
- **GET /listings/{listing}/edit** - Edit form
- **PUT /listings/{listing}** - Update (resets to pending if previously approved/rejected)

### Admin Moderation
- **GET /admin/listings** - Pending listings
- **POST /admin/listings/{listing}/approve** - Approve (sets published_at, clears rejection_reason)
- **POST /admin/listings/{listing}/reject** - Reject (requires reason)

---

## Business Logic (Service Layer)

All logic in `app/Services/ListingService`:
- Create: status=pending, published_at=null
- Update: resets status to pending if previously approved/rejected
- Approve: published_at=now(), rejection_reason=null
- Reject: requires reason, keeps published_at=null

Authorization via `app/Policies/ListingPolicy`:
- Providers can only edit own listings
- Admins can moderate all listings
- Pending/rejected never publicly visible

---

## JavaScript Enhancements (Progressive)

**JS-1: Debounced Search Filters** (`/listings`)
- Keyword (q): 400ms debounce
- City: immediate submit
- Sort: immediate submit

**JS-2: Confirm Dialogs** (`/admin/listings`)
- Approve: confirm dialog
- Reject: confirm dialog + validates reason

Both work without JavaScript (forms submit normally).

---

## Tests

```bash
php artisan test --filter ListingTest
```

### Tests Included
1. Service creates listing with status=pending & published_at=null
2. Service approves listing, sets published_at & clears rejection_reason
3. Public /listings shows only approved listings
4. Provider cannot edit another user's listing (403)

---

## Architecture

**Why Form Requests?**
- Centralized validation logic
- Clean, consistent error handling
- Move validation out of controllers

**Why Service Layer?**
- Single source of truth for business rules
- Easy to test
- Reusable across controllers

**Why Policies?**
- Clean authorization checks
- Consistent with Laravel conventions
- Easy to maintain

**Thin Controllers**
- Receive → Authorize → Call Service → Respond
- No business logic
- Easy to understand

---

## Project Structure

```
app/Http/Controllers/
  ├── ListingController.php (public + provider CRUD)
  └── Admin/ListingModerationController.php

app/Http/Requests/
  ├── StoreListingRequest.php
  ├── UpdateListingRequest.php
  └── RejectListingRequest.php

app/Models/
  ├── Listing.php
  └── User.php

app/Policies/
  └── ListingPolicy.php

app/Services/
  └── ListingService.php

resources/views/
  ├── listings/
  │   ├── index.blade.php
  │   ├── create.blade.php
  │   ├── edit.blade.php
  │   └── show.blade.php
  ├── admin/listings/
  │   └── index.blade.php
  └── dashboard.blade.php

tests/Feature/
  └── ListingTest.php
```

---

## Next Steps (Extension Ideas)

- Image uploads
- Email notifications
- Categories/subcategories
- Expiration logic
- User messaging
- Admin dashboard/stats
- Rate limiting
- CSV export

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
"# Listing-app" 
