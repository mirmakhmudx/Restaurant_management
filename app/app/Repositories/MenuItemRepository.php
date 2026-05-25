<?php

namespace App\Repositories;

use App\Contracts\MenuItemRepositoryInterface;
use App\Enums\MenuItemType;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;

/**
 * Concrete implementation of MenuItemRepositoryInterface.
 * All database queries for MenuItems go through here.
 *
 * Design Pattern: Repository
 */
class MenuItemRepository implements MenuItemRepositoryInterface
{
    public function all(): Collection
    {
        return MenuItem::orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id): ?MenuItem
    {
        return MenuItem::findOrFail($id);
    }

    public function findByType(MenuItemType $type): Collection
    {
        return MenuItem::ofType($type)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function findAvailable(): Collection
    {
        return MenuItem::available()
            ->orderBy('type')
            ->orderBy('name')
            ->get();
    }

    public function search(string $query): Collection
    {
        return MenuItem::search($query)
            ->orderBy('type')
            ->orderBy('name')
            ->get();
    }

    public function create(array $data): MenuItem
    {
        return MenuItem::create($data);
    }

    public function update(MenuItem $item, array $data): MenuItem
    {
        $item->update($data);
        return $item->fresh();
    }

    public function delete(MenuItem $item): bool
    {
        return $item->delete();
    }

    public function toggleAvailability(MenuItem $item): MenuItem
    {
        $item->update(['is_available' => ! $item->is_available]);
        return $item->fresh();
    }
}
