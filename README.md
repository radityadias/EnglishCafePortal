# English Cafe Portal

A web-based employee management and attendance system built for English Cafe. It handles location-based attendance tracking, employee performance monitoring, and daily activity reporting for both employees and interns.

## Features

* **Geofenced Attendance** — Employees check in/out via GPS, validated against their assigned branch's location radius. Includes automatic lateness detection with tiered warning levels (low/medium/high) based on how late a check-in is relative to scheduled work hours.
* **Attendance Requests** — Manual correction workflow for when a scan isn't possible (e.g. device issues, forgotten check-in). Requests go through an approval flow before affecting attendance records.
* **Employee Performance Index (KPI)** — Monthly, filterable dashboard tracking attendance percentage, class counts (theory / daily talk), appointment closing rate, and revenue — scoped to KPI-tracked divisions (Master Chef, Operational Chef).
* **Daily Progress Reports** — Class session logging for tracking teaching activity.
* **Employee \& Internship Profiles** — Separate profile types for full-time employees and interns, covering personal info, work schedule, bank details, and required documents (CV, KTP), stored via S3-compatible storage.
* **Role-Based Access Control** — Powered by Filament Shield, with Super Admin, Admin, and User roles controlling access across resources and pages.
* **Data Export** — Filterable attendance and report exports (by month/year), processed asynchronously via Laravel's queue system.

## Tech Stack

* [**Laravel**](https://laravel.com) — PHP application framework
* [**Filament**](https://filamentphp.com) — Admin panel and resource management
* [**Filament Shield**](https://github.com/bezhanSalleh/filament-shield) — Role and permission management
* [**Livewire**](https://livewire.laravel.com) — Reactive UI components
* **PostgreSQL** (via [Supabase](https://supabase.com)) — Primary database
* **Laravel Queues** — Background job processing (exports, KPI updates, notifications)

## Getting Started

### Requirements

* PHP 8.3+
* Composer
* Node.js \& npm
* PostgreSQL database

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd english-cafe-portal

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy and configure environment
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database and storage (S3) credentials, then:

```bash
# Run migrations
php artisan migrate

# Publish Filament's job batching migrations (required for exports)
php artisan vendor:publish --tag=filament-actions-migrations
php artisan migrate

# Generate Filament Shield permissions
php artisan shield:generate --all

# Build frontend assets
npm run build

# Serve the application
php artisan serve
```

### Running the Queue Worker

Exports, KPI updates, and other background jobs require an active queue worker:

```bash
php artisan queue:work
```

### Running the Scheduler

Scheduled tasks are defined in `routes/console.php`. In production, add this to your crontab:

```bash
\\\* \\\* \\\* \\\* \\\* cd /path-to-project \\\&\\\& php artisan schedule:run >> /dev/null 2>\\\&1
```

## License

Proprietary — internal use for English Cafe.

