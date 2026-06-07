<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuModifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuModifierController extends Controller
{
    public function index(MenuItem $menuItem): View
    {
        $modifiers = $menuItem->modifiers()->get();
        return view('menu.modifiers.index', compact('menuItem', 'modifiers'));
    }

    public function store(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'price'      => ['required', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? $menuItem->modifiers()->max('sort_order') + 1;
        $menuItem->modifiers()->create($data);

        return back()->with('success', "'{$data['name']}' modifier qo'shildi.");
    }

    public function toggle(MenuModifier $modifier): RedirectResponse
    {
        $modifier->update(['is_available' => !$modifier->is_available]);
        return back()->with('success', "Modifier holati yangilandi.");
    }

    public function destroy(MenuModifier $modifier): RedirectResponse
    {
        $menuItem = $modifier->menuItem;
        $modifier->delete();
        return redirect()->route('menu.modifiers.index', $menuItem)
            ->with('success', "Modifier o'chirildi.");
    }
}
