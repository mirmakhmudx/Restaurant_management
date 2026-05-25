<?php

namespace App\Contracts;

use App\Enums\MenuItemType;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository contract for MenuItem data access.
 * Separates business logic from database implementation.
 */
interface MenuItemRepositoryInterface
{
    public function all(): Collection;
    public function findById(int $id): ?MenuItem;
    public function findByType(MenuItemType $type): Collection;
    public function findAvailable(): Collection;
    public function search(string $query): Collection;
    public function create(array $data): MenuItem;
    public function update(MenuItem $item, array $data): MenuItem;
    public function delete(MenuItem $item): bool;
    public function toggleAvailability(MenuItem $item): MenuItem;
}
