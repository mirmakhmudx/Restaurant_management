<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ComboController extends Controller
{
    public function index(): View
    {
        $combos = Combo::with('items.menuItem')->latest()->paginate(10);
        return view('combos.index', compact('combos'));
    }

    public function create(): View
    {
        $menuItems = MenuItem::where('is_available', true)->orderBy('name')->get();
        return view('combos.create', compact('menuItems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'items'          => ['required', 'array', 'min:2'],
            'items.*.id'     => ['required', 'exists:menu_items,id'],
            'items.*.qty'    => ['required', 'integer', 'min:1'],
        ]);

        $combo = Combo::create($request->only('name', 'description', 'price'));

        foreach ($request->items as $item) {
            $combo->items()->create([
                'menu_item_id' => $item['id'],
                'quantity'     => $item['qty'],
            ]);
        }

        return redirect()->route('combos.index')
            ->with('success', 'Combo yaratildi!');
    }

    public function show(Combo $combo): View
    {
        $combo->load('items.menuItem');
        return view('combos.show', compact('combo'));
    }

    public function destroy(Combo $combo): RedirectResponse
    {
        $combo->delete();
        return redirect()->route('combos.index')
            ->with('success', 'Combo o\'chirildi!');
    }
}
