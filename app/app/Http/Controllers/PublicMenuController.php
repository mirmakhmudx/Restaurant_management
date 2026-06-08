<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicMenuController extends Controller
{
    public function index(Request $request): View
    {
        $table = $request->query('table')
            ? \App\Models\Table::find($request->query('table'))
            : null;

        $items = \App\Models\MenuItem::with('activeModifiers')
            ->where('is_available', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $grouped = $items->groupBy(fn($i) => $i->type->value);

        // Combo items
        $combos = \App\Models\Combo::with('items.menuItem')
            ->where('is_active', true)
            ->get();

        $menuItems = $items->map(fn($item) => [
            'id'        => $item->id,
            'name'      => $item->name,
            'desc'      => $item->description ?? '',
            'price'     => (float) $item->price,
            'icon'      => $item->type->icon(),
            'image'     => $item->getImageUrl() ?? '',
            'type'      => $item->type->label(),
            'prep'      => $item->prep_time_minutes,
            'cal'       => $item->calories,
            'veg'       => (bool) $item->is_vegetarian,
            'vegan'     => (bool) $item->is_vegan,
            'available' => (bool) $item->is_available,
            'is_combo'  => false,
            'mods'      => $item->activeModifiers->map(fn($m) => [
                'id'    => $m->id,
                'name'  => $m->name,
                'price' => (float) $m->price,
            ])->values()->toArray(),
        ])->values()->toArray();

        // Comboları ham qo'shamiz
        $comboItems = $combos->map(fn($combo) => [
            'id'        => 'combo_' . $combo->id,
            'name'      => $combo->name . ' 🍱',
            'desc'      => $combo->description ?? implode(', ', $combo->items->map(fn($i) => $i->quantity.'x '.$i->menuItem->name)->toArray()),
            'price'     => (float) $combo->price,
            'icon'      => '🍱',
            'image'     => '',
            'type'      => 'Combo Sets',
            'prep'      => 15,
            'cal'       => null,
            'veg'       => false,
            'vegan'     => false,
            'available' => true,
            'is_combo'  => true,
            'savings'   => (float) $combo->getSavings(),
            'mods'      => [],
        ])->values()->toArray();

        $allItems = array_merge($menuItems, $comboItems);

        return view('menu.public', compact('table', 'items', 'grouped', 'menuItems', 'combos', 'allItems'));
    }

    public function order(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'table_id' => ['nullable'],
            'items'    => ['required', 'array', 'min:1'],
            'items.*.id'       => ['required', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $order = \App\Models\Order::create([
            'order_number' => 'BP-' . str_pad(\App\Models\Order::count() + 1, 4, '0', STR_PAD_LEFT),            'table_id'  => $request->table_id ?: null,
            'waiter_id' => null,
            'status'    => 'pending',
            'notes'     => 'QR orqali buyurtma',
            'subtotal'  => 0,
            'discount'  => 0,
            'total'     => 0,
        ]);
        $subtotal = 0;
        foreach ($request->items as $item) {
            $menuItem = MenuItem::find($item['id']);
            $order->items()->create([
                'menu_item_id' => $menuItem->id,
                'name'         => $menuItem->name,
                'price'        => $menuItem->price,
                'quantity'     => $item['quantity'],
                'notes'        => $item['notes'] ?? null,
                'subtotal'     => $menuItem->price * $item['quantity'],
            ]);
            $subtotal += $menuItem->price * $item['quantity'];
        }

        $order->update(['subtotal' => $subtotal, 'total' => $subtotal]);

        if ($request->table_id) {
            Table::find($request->table_id)?->update(['status' => 'occupied']);
        }

        return response()->json([
            'success'      => true,
            'order_number' => $order->order_number,
        ]);
    }
}
