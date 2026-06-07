<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuCategoryController extends Controller
{
    public function index(): View
    {
        $categories = MenuCategory::orderBy('sort_order')->get();
        return view('menu.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:50'],
            'icon'       => ['required', 'string', 'max:10'],
            'sort_order' => ['nullable', 'integer'],
        ]);
        $data['sort_order'] = $data['sort_order'] ?? MenuCategory::max('sort_order') + 1;

        MenuCategory::create($data);

        return redirect()->route('menu.categories.index')
            ->with('success', "'{$data['name']}' kategoriyasi qo'shildi.");
    }

    public function destroy(MenuCategory $menuCategory): RedirectResponse
    {
        $menuCategory->delete();
        return redirect()->route('menu.categories.index')
            ->with('success', "Kategoriya o'chirildi.");
    }

    public function toggleActive(MenuCategory $menuCategory): RedirectResponse
    {
        $menuCategory->update(['is_active' => !$menuCategory->is_active]);
        return back()->with('success', "Kategoriya holati yangilandi.");
    }
}
