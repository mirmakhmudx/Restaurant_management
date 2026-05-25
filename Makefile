.PHONY: help build up down restart logs shell artisan composer npm migrate fresh seed test

# ── Colors ────────────────────────────────────────────
GREEN  := \033[0;32m
YELLOW := \033[0;33m
CYAN   := \033[0;36m
RESET  := \033[0m

help: ## Barcha buyruqlarni ko'rsatish
	@echo ""
	@echo "$(CYAN)╔══════════════════════════════════════╗$(RESET)"
	@echo "$(CYAN)║     BitePlate SRMS — Make Commands   ║$(RESET)"
	@echo "$(CYAN)╚══════════════════════════════════════╝$(RESET)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "$(GREEN)  %-18s$(RESET) %s\n", $$1, $$2}'
	@echo ""

# ── Docker ────────────────────────────────────────────
build: ## Docker imagelarni build qilish
	docker compose build --no-cache

up: ## Barcha servislarni ishga tushirish
	docker compose up -d
	@echo "$(GREEN)✅ BitePlate running on http://localhost:8000$(RESET)"
	@echo "$(GREEN)✅ pgAdmin running on http://localhost:5050$(RESET)"

down: ## Servislarni to'xtatish
	docker compose down

down-v: ## Servislar + volumelarni o'chirish
	docker compose down -v

restart: ## Qayta ishga tushirish
	docker compose restart

logs: ## Loglarni ko'rish
	docker compose logs -f

logs-app: ## Faqat app loglarini ko'rish
	docker compose logs -f app

# ── Laravel ───────────────────────────────────────────
install: ## Laravel o'rnatish (birinchi marta)
	docker compose exec app composer create-project laravel/laravel . --prefer-dist
	docker compose exec app cp .env.example .env
	docker compose exec app php artisan key:generate
	@echo "$(GREEN)✅ Laravel installed!$(RESET)"

artisan: ## php artisan buyrug'i (make artisan CMD="migrate")
	docker compose exec app php artisan $(CMD)

composer: ## Composer buyrug'i (make composer CMD="require package")
	docker compose exec app composer $(CMD)

npm: ## NPM buyrug'i (make npm CMD="install")
	docker compose exec app npm $(CMD)

# ── Database ──────────────────────────────────────────
migrate: ## Migrationlarni ishga tushirish
	docker compose exec app php artisan migrate

fresh: ## Bazani tozalab qayta migrate qilish
	docker compose exec app php artisan migrate:fresh --seed

seed: ## Seed datalarni yuklash
	docker compose exec app php artisan db:seed

# ── Development ───────────────────────────────────────
shell: ## App container ichiga kirish
	docker compose exec app sh

shell-root: ## Root sifatida kirish
	docker compose exec --user root app sh

postgres-shell: ## PostgreSQL ichiga kirish
	docker compose exec postgres psql -U biteplate -d biteplate

test: ## Testlarni ishga tushirish
	docker compose exec app php artisan test

cache-clear: ## Cache tozalash
	docker compose exec app php artisan cache:clear
	docker compose exec app php artisan config:clear
	docker compose exec app php artisan route:clear
	docker compose exec app php artisan view:clear

# ── First Time Setup ──────────────────────────────────
setup: build up install migrate seed ## Birinchi marta to'liq o'rnatish
	@echo "$(CYAN)🍽️  BitePlate SRMS tayyor!$(RESET)"
	@echo "$(CYAN)   App:     http://localhost:8000$(RESET)"
	@echo "$(CYAN)   pgAdmin: http://localhost:5050$(RESET)"
