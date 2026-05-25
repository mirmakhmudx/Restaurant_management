<?php

namespace App\Services;

use App\Contracts\MenuItemInterface;
use App\Contracts\MenuItemRepositoryInterface;
use App\Decorators\ChefSpecialDecorator;
use App\Decorators\HappyHourDecorator;
use App\Decorators\SpecialOfferDecorator;
use App\Enums\MenuItemType;
use App\Factories\MenuItemFactory;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;

/**
 * Business logic layer for MenuItem operations.
 * Uses Factory to create items and Decorators to apply runtime behaviour.
 *
 * Design Pattern: Service Layer
 */
class MenuItemService
{
    public function __construct(
        private readonly MenuItemRepositoryInterface $repository,
        private readonly MenuItemFactory $factory
    ) {}

    /**
     * Get all items grouped by type for the menu index view.
     */
    public function getAllGroupedByType(): array
    {
        return $this->repository->all()
            ->groupBy(fn(MenuItem $item) => $item->type->value)
            ->toArray();
    }

    /**
     * Get all items as Eloquent Collection.
     */
    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    /**
     * Create a new menu item via Factory (applies type-based defaults).
     */
    public function create(array $data): MenuItem
    {
        return $this->factory->create($data);
    }

    /**
     * Update an existing menu item.
     */
    public function update(MenuItem $item, array $data): MenuItem
    {
        return $this->repository->update($item, $data);
    }

    /**
     * Soft delete a menu item.
     */
    public function delete(MenuItem $item): bool
    {
        return $this->repository->delete($item);
    }

    /**
     * Toggle available/unavailable status.
     */
    public function toggleAvailability(MenuItem $item): MenuItem
    {
        return $this->repository->toggleAvailability($item);
    }

    /**
     * Search menu items by name or description.
     */
    public function search(string $query): Collection
    {
        return $this->repository->search($query);
    }

    /**
     * Apply Chef's Special decorator — Decorator Pattern.
     */
    public function asChefSpecial(MenuItem $item): MenuItemInterface
    {
        return new ChefSpecialDecorator($item);
    }

    /**
     * Apply Special Offer decorator with given discount — Decorator Pattern.
     */
    public function withSpecialOffer(MenuItem $item, float $discountPercent): MenuItemInterface
    {
        return new SpecialOfferDecorator($item, $discountPercent);
    }

    /**
     * Apply Happy Hour decorator — Decorator Pattern.
     */
    public function withHappyHour(MenuItem $item): MenuItemInterface
    {
        return new HappyHourDecorator($item);
    }

    /**
     * Get available items by type (used by Order form).
     */
    public function getAvailableByType(MenuItemType $type): Collection
    {
        return $this->repository->findByType($type)
            ->filter(fn(MenuItem $item) => $item->is_available);
    }
}
