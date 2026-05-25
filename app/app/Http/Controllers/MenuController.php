<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Models\MenuItem;
use App\Services\MenuItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function __construct(
        private readonly MenuItemService $service
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search', '');
        $type   = $request->query('type', 'all');

        $items = $search
            ? $this->service->search($search)
            : $this->service->getAll();

        if ($type !== 'all') {
            $items = $items->filter(fn($item) => $item->type->value === $type);
        }

        $grouped = $items->groupBy(fn($item) => $item->type->value);

        return view('menu.index', compact('items', 'grouped', 'search', 'type'));
    }

    public function create(): View
    {
        return view('menu.create');
    }

    public function store(StoreMenuItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_available']   = $request->boolean('is_available', true);
        $data['is_vegetarian']  = $request->boolean('is_vegetarian');
        $data['is_vegan']       = $request->boolean('is_vegan');
        $data['is_gluten_free'] = $request->boolean('is_gluten_free');
        $data['allergens']      = $request->input('allergens', []);

        $item = $this->service->create($data);

        return redirect()->route('menu.index')
            ->with('success', "✅ \"{$item->name}\" added to menu successfully.");
    }

    public function edit(MenuItem $menuItem): View
    {
        return view('menu.edit', ['item' => $menuItem]);
    }

    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $request->validated();
        $data['is_available']   = $request->boolean('is_available', true);
        $data['is_vegetarian']  = $request->boolean('is_vegetarian');
        $data['is_vegan']       = $request->boolean('is_vegan');
        $data['is_gluten_free'] = $request->boolean('is_gluten_free');
        $data['allergens']      = $request->input('allergens', []);

        $this->service->update($menuItem, $data);

        return redirect()->route('menu.index')
            ->with('success', "✅ \"{$menuItem->name}\" updated successfully.");
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        $name = $menuItem->name;
        $this->service->delete($menuItem);

        return redirect()->route('menu.index')
            ->with('success', "🗑️ \"{$name}\" removed from menu.");
    }

    /**
     * Toggle available/unavailable via AJAX.
     */
    public function toggleAvailability(MenuItem $menuItem): JsonResponse
    {
        $updated = $this->service->toggleAvailability($menuItem);

        return response()->json([
            'success'      => true,
            'is_available' => $updated->is_available,
            'message'      => $updated->is_available
                ? "{$updated->name} is now available"
                : "{$updated->name} marked as unavailable",
        ]);
    }

    /**
     * Live search endpoint for Alpine.js / AJAX.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->query('q', '');
        $items = $query ? $this->service->search($query) : $this->service->getAll();

        return response()->json($items->values());
    }
}
