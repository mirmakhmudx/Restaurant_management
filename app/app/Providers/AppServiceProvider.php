<?php

namespace App\Providers;

use App\Contracts\MenuItemRepositoryInterface;
use App\Models\Order;
use App\Observers\OrderObserver;
use App\Repositories\MenuItemRepository;
use App\Services\BillingFacade;
use App\Services\KitchenQueue;
use App\Services\OrderHistoryService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MenuItemRepositoryInterface::class, MenuItemRepository::class);

        // Singleton Pattern
        $this->app->singleton(OrderHistoryService::class, fn () => new OrderHistoryService());

        // Command Pattern invoker
        $this->app->singleton(KitchenQueue::class);

        // Facade Pattern
        $this->app->singleton(BillingFacade::class);
    }

    public function boot(): void
    {
        // Observer Pattern
        Order::observe(OrderObserver::class);
    }
}
