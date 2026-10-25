# Equipment Borrowing App

Repository: https://github.com/0xyz-exe/equipment-borrowing-app-laravel

A streamlined equipment lending and reservation management system built with Laravel 13, Blade, Tailwind CSS, and SQLite.

## Features

- ✅ **Authentication & Roles**: Laravel Breeze auth with Administrator and User role separation.
- ✅ **Equipment Catalog**: Browse items with category, code, condition, and availability status.
- ✅ **Borrow Requests**: Users can submit borrow requests specifying dates and purpose.
- ✅ **Approval Workflow**: Admins review, approve, or reject pending requests with notes.
- ✅ **Return Management**: Mark equipment as returned and restore item availability.
- ✅ **Request History**: Complete audit trail of active, past, and rejected borrow requests.

## Tech Stack

Laravel 13.38.0 · Breeze 2.4 · SQLite · Tailwind CSS

## Installation

```bash
git clone https://github.com/0xyz-exe/equipment-borrowing-app-laravel.git
cd equipment-borrowing-app-laravel
composer install && npm install && npm run build
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

### Demo Accounts

All accounts use the password: `password`

- **Admin**: `admin@example.com` (Approve/reject requests, manage equipment catalog)
- **User**: `test@example.com` (Submit borrow requests, view personal history)

## Database Schema

- **users**: `id`, `name`, `email`, `is_admin`, `password`, `timestamps`
- **equipment**: `id`, `name`, `code`, `category`, `description`, `condition` (Good/Fair/Poor), `is_available`, `timestamps`
- **borrow_requests**: `id`, `user_id`, `equipment_id`, `purpose`, `borrow_date`, `return_date`, `actual_return_date`, `status` (pending/approved/rejected/returned), `admin_notes`, `timestamps`

## License

MIT
