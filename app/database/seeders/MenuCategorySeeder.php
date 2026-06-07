<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Starter',     'icon' => '🥗', 'slug' => 'starter',      'sort_order' => 1],
            ['name' => 'Main Course', 'icon' => '🍽️', 'slug' => 'main_course',  'sort_order' => 2],
            ['name' => 'Dessert',     'icon' => '🍰', 'slug' => 'dessert',       'sort_order' => 3],
            ['name' => 'Beverage',    'icon' => '🥤', 'slug' => 'beverage',      'sort_order' => 4],
        ];

        foreach ($categories as $cat) {
            MenuCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
