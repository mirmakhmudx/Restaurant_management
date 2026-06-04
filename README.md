
# BitePlate — Smart Restaurant Management System (SRMS)

**BTEC Level 5 HND Computing — Unit 27: Advanced Programming**

## Project Overview

BitePlate SRMS is a full-stack restaurant management platform built with PHP 8.3 and Laravel 13. The system supports four user roles (Manager, Waiter, Chef, Cashier) and implements ten software design patterns across its architecture.

**Tech Stack:**
- PHP 8.3 + Laravel 13
- PostgreSQL 16
- Docker Compose
- Alpine.js + Tailwind CSS
- Laravel Reverb (WebSocket)
- DomPDF (PDF receipts)

## Design Patterns Implemented

| Pattern | File Location | Purpose |
|---------|--------------|---------|
| Singleton | `app/Services/OrderHistoryService.php` | Global DB-backed audit log |
| Strategy | `app/Strategies/` | Dynamic pricing (Standard/HappyHour/Loyalty/Staff) |
| Command | `app/Commands/` + `app/Services/KitchenQueue.php` | Kitchen queue with undo |
| Observer | `app/Observers/OrderObserver.php` | Order lifecycle notifications |
| State | `app/States/` | Order status machine (7 states) |
| Repository | `app/Repositories/MenuItemRepository.php` | Data access abstraction |
| Factory | `app/Factories/MenuItemFactory.php` | Type-based object creation |
| Facade | `app/Services/BillingFacade.php` | Billing subsystem API |
| Service Layer | `app/Services/MenuItemService.php` | Business logic separation |
| Composite | `app/Models/Combo.php` + `ComboItem.php` | Uniform menu component treatment |

## Installation

### Prerequisites
- Docker Desktop installed and running
- Git

### Steps

```bash
# 1. Clone the repository
git clone <repository-url>
cd biteplate

# 2. Copy environment file
cp app/.env.example app/.env

# 3. Start Docker containers
docker compose up -d

# 4. Install PHP dependencies
docker compose exec app composer install

# 5. Generate application key
docker compose exec app php artisan key:generate

# 6. Run database migrations
docker compose exec app php artisan migrate

# 7. Seed demo data
docker compose exec app php artisan db:seed

# 8. Build frontend assets
docker compose exec app npm install
docker compose exec app npm run build

# 9. Create storage symlink
docker compose exec app php artisan storage:link
```

## Running the Application

```bash
# Start all services
docker compose up -d

# Access the application
open http://localhost:8000

# Access pgAdmin (database)
open http://localhost:5050
```

## Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| Manager | manager@biteplate.com | password |
| Waiter | waiter@biteplate.com | password |
| Chef | chef@biteplate.com | password |
| Cashier | cashier@biteplate.com | password |

## Key Features

- **Role-based dashboards** — each role sees a tailored interface
- **Kitchen Kanban board** — real-time order status tracking
- **QR Code ordering** — customers scan table QR and order directly
- **Dynamic pricing** — Strategy pattern with 4 pricing modes
- **Split billing** — divide bills among multiple guests
- **PDF receipts** — generated via DomPDF
- **Order history log** — persistent Singleton audit trail
- **Combo meals** — Composite pattern for set menus
- **Real-time notifications** — Laravel Reverb WebSocket
- **Reservation calendar** — date-based booking management
- **Staff shift tracking** — clock-in/clock-out system
- **Analytics dashboard** — revenue, peak hour, top dish metrics

## Running Tests

```bash
docker compose exec app php artisan test
```

## Useful Commands

```bash
# Clear all caches
docker compose exec app php artisan optimize:clear

# View logs
docker compose exec app php artisan pail

# Start Reverb WebSocket server
docker compose exec app php artisan reverb:start --host=0.0.0.0 --port=8080

# Run queue worker
docker compose exec app php artisan queue:work
```

## Project Structure