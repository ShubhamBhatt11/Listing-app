# Listings + Moderation (Laravel + Blade + Vanilla JS + Tailwind)

Server-rendered Laravel monolith where providers create listings, admins moderate them, and the public can browse approved listings.

## Tech choices

- Laravel + Blade (no SPA)
- Tailwind CSS
- Vanilla JavaScript (progressive enhancement only)

## Setup

1. Install dependencies
   - `composer install`
   - `npm install`
2. Environment
   - `cp .env.example .env`
   - `php artisan key:generate`
3. Database
   - `php artisan migrate:fresh --seed`
4. Frontend assets
   - `npm run dev`
5. Run app
   - `php artisan serve`

## Seeded credentials

- Admin
  - Email: `admin@example.com`
  - Password: `password`
- Provider
  - Email: `provider@example.com`
  - Password: `password`

Seeded listings: 15 total
- approved: 9
- pending: 4
- rejected: 2

## Route summary

- Public
  - `GET /listings`
  - `GET /listings/{listing}` (approved only)
- Provider
  - `GET /dashboard`
  - `GET /listings/create`
  - `POST /listings`
  - `GET /listings/{listing}/edit`
  - `PUT /listings/{listing}`
- Admin
  - `GET /admin/listings`
  - `POST /admin/listings/{listing}/approve`
  - `POST /admin/listings/{listing}/reject`

## JavaScript enhancements (progressive)

- `/listings`
  - `q` auto-submit with 400ms debounce
  - `city` and `sort` submit immediately on change
  - Search button remains visible for no-JS fallback
- `/admin/listings`
  - Confirm on approve: "Approve this listing?"
  - Confirm on reject: "Reject this listing with this reason?"
  - No-JS fallback works via normal form submits

## Run tests

- Run all tests: `php artisan test`
- Run interview-required 4 tests with one command: `composer test:interview`
- Run only required Unit tests: `php artisan test tests/Unit/ListingServiceTest.php`
- Run only required Feature tests: `php artisan test tests/Feature/ListingAccessTest.php`
- Run exactly the 4 required tests by name filters:
  - `php artisan test --filter test_create_listing_sets_status_pending_and_published_at_null`
  - `php artisan test --filter test_approve_pending_sets_published_at_and_clears_rejection_reason`
  - `php artisan test --filter test_public_listings_shows_only_approved`
  - `php artisan test --filter test_provider_cannot_edit_another_users_listing`

## Architecture notes

- Form Requests handle input validation:
  - `StoreListingRequest`
  - `UpdateListingRequest`
  - `RejectListingRequest`
- `ListingPolicy` enforces ownership/moderation authorization.
- `ListingService` contains listing business rules (create/update/approve/reject).
- Controllers are thin: authorize -> call service -> return response.

## Next steps (1–2 days)

- Add dedicated middleware tests for provider/admin route boundaries.
- Add request-level and service-level tests for invalid moderation transitions.
- Improve UX copy/empty states while keeping server-rendered flow.

## Submission checklist

- Seeded admin + provider accounts included
- Provider dashboard lists only provider-owned listings
- Provider create/edit flows implemented with status reset rules
- Public listing filters + pagination preserve query params
- Public detail page returns 404 for non-approved listings
- Admin pending list + approve/reject moderation routes implemented
- Progressive enhancement JS implemented (debounce + confirm dialogs)
- Required 4 tests implemented and runnable via `composer test:interview`
- Architecture uses Form Requests + Policy + Service layer + thin controllers
