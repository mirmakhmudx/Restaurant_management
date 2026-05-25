# 🍽️ BitePlate SRMS
### Smart Restaurant Management System
**Unit 27: Advanced Programming · BTEC Level 5**

---

## Stack
| Qatlam | Texnologiya |
|--------|-------------|
| Backend | Laravel 11 (PHP 8.3) |
| Frontend | Blade + Alpine.js |
| Database | PostgreSQL 16 |
| Cache/Queue | Redis 7 |
| Web Server | Nginx 1.25 |
| Container | Docker + Docker Compose |

---

## ⚡ Tezkor Ishga Tushirish

### 1. Talablar
- Docker Desktop o'rnatilgan bo'lsin
- Docker Compose v2+ bo'lsin

### 2. Loyihani Clone qilish
```bash
git clone <repo_url> biteplate
cd biteplate
```

### 3. Birinchi Marta O'rnatish
```bash
# Usul 1 — Makefile bilan (tavsiya)
make setup

# Usul 2 — Qo'lda
docker compose build
docker compose up -d
docker compose exec app composer create-project laravel/laravel . --prefer-dist
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

### 4. Ochish
| Servis | URL |
|--------|-----|
| **BitePlate App** | http://localhost:8000 |
| **pgAdmin** | http://localhost:5050 |

pgAdmin login:
- Email: `admin@biteplate.com`
- Password: `admin`

---

## 📦 Servislar

```
biteplate_app      → PHP 8.3-FPM (Laravel)
biteplate_nginx    → Nginx 1.25 (Port 8000)
biteplate_postgres → PostgreSQL 16 (Port 5432)
biteplate_redis    → Redis 7 (Port 6379)
biteplate_queue    → Laravel Queue Worker
biteplate_pgadmin  → pgAdmin 4 (Port 5050)
```

---

## 🎯 Design Patterns (10 ta)

| # | Pattern | Sinf/Fayl |
|---|---------|-----------|
| 1 | **Singleton** | `OrderHistoryService` |
| 2 | **Command** | `KitchenQueueService` + Commands |
| 3 | **Strategy** | `PricingStrategy` interfeysi |
| 4 | **Repository** | `OrderRepository` |
| 5 | **Observer** | `OrderObserver` + Events |
| 6 | **Factory** | `MenuItemFactory` |
| 7 | **Facade** | `BillingService` |
| 8 | **State** | `OrderState` (Pending → Billed) |
| 9 | **Service Layer** | `OrderService` |
| 10 | **Decorator** | `MenuItemDecorator` |

---

## 🔧 Foydali Buyruqlar

```bash
make up           # Ishga tushirish
make down         # To'xtatish
make shell        # Container ichiga kirish
make migrate      # Migration
make fresh        # DB tozalab qayta migrate
make seed         # Test data yuklash
make logs         # Loglarni ko'rish
make test         # Testlar
make cache-clear  # Cache tozalash
```

---

## 🗄️ Ma'lumotlar Bazasi

```
Host:     localhost
Port:     5432
Database: biteplate
Username: biteplate
Password: biteplate_secret
```

---

## 📁 Loyiha Strukturasi

```
biteplate/
├── app/                    ← Laravel application
│   ├── Contracts/          ← PHP Interfaces
│   ├── Services/           ← Business logic (patterns)
│   ├── Commands/           ← Command Pattern classes
│   ├── Pricing/            ← Strategy Pattern classes
│   ├── Repositories/       ← Repository Pattern
│   ├── Observers/          ← Observer Pattern
│   ├── States/             ← State Pattern
│   ├── Factories/          ← Factory Pattern
│   ├── Models/             ← Eloquent Models
│   └── Http/Controllers/   ← API + Web Controllers
├── docker/
│   ├── nginx/default.conf  ← Nginx config
│   └── php/php.ini         ← PHP settings
├── docker-compose.yml      ← Servislar
├── Dockerfile              ← PHP image
├── Makefile                ← Qulay buyruqlar
└── .env.example            ← Environment namuna
```
