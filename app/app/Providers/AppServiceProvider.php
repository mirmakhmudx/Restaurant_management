<?php

namespace App\Providers;

use App\Contracts\MenuItemRepositoryInterface;
use App\Repositories\MenuItemRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ── Repository Bindings — Repository Pattern ───
        $this->app->bind(
            MenuItemRepositoryInterface::class,
            MenuItemRepository::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
