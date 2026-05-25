<?php

namespace Database\Seeders;

use App\Enums\MenuItemType;
use App\Factories\MenuItemFactory;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(MenuItemFactory $factory): void
    {
        $items = [
            // ── Starters ──────────────────────────────
            ['name'=>'Soup of the Day',         'type'=>'starter',     'price'=>6.50,  'description'=>'Ask your waiter for today\'s selection, served with crusty bread.', 'allergens'=>['gluten','dairy'], 'is_vegetarian'=>true],
            ['name'=>'Crispy Calamari',          'type'=>'starter',     'price'=>9.50,  'description'=>'Lightly battered squid rings with lemon aioli and fresh herbs.', 'allergens'=>['gluten','eggs','shellfish']],
            ['name'=>'Bruschetta al Pomodoro',   'type'=>'starter',     'price'=>7.00,  'description'=>'Toasted sourdough with fresh tomato, basil and extra virgin olive oil.', 'allergens'=>['gluten'], 'is_vegetarian'=>true, 'is_vegan'=>true],
            ['name'=>'Smoked Salmon Blini',      'type'=>'starter',     'price'=>11.50, 'description'=>'Scottish smoked salmon on buckwheat blinis with crème fraîche.', 'allergens'=>['gluten','dairy','eggs','fish']],

            // ── Main Courses ───────────────────────────
            ['name'=>'Grilled Sea Bass',          'type'=>'main_course', 'price'=>22.00, 'description'=>'Pan-seared sea bass fillet on creamy mashed potato with samphire and lemon butter.', 'allergens'=>['dairy','fish'], 'prep_time_minutes'=>25],
            ['name'=>'Slow-Braised Lamb Shank',   'type'=>'main_course', 'price'=>24.50, 'description'=>'12-hour braised lamb on rosemary polenta with roasted root vegetables.', 'allergens'=>['dairy'], 'prep_time_minutes'=>20],
            ['name'=>'Wild Mushroom Risotto',      'type'=>'main_course', 'price'=>16.50, 'description'=>'Arborio rice with porcini and chestnut mushrooms, truffle oil and parmesan.', 'allergens'=>['dairy'], 'is_vegetarian'=>true, 'prep_time_minutes'=>18],
            ['name'=>'8oz Beef Sirloin',           'type'=>'main_course', 'price'=>29.00, 'description'=>'28-day aged sirloin served with triple-cooked chips, watercress and béarnaise.', 'allergens'=>['dairy','eggs'], 'prep_time_minutes'=>25, 'calories'=>720],
            ['name'=>'Roasted Chicken Supreme',    'type'=>'main_course', 'price'=>19.50, 'description'=>'Free-range chicken with dauphinoise potato, tenderstem broccoli and jus.', 'allergens'=>['dairy'], 'prep_time_minutes'=>22],
            ['name'=>'Vegan Wellington',           'type'=>'main_course', 'price'=>17.00, 'description'=>'Beetroot and lentil wellington with roasted squash purée and kale.', 'allergens'=>['gluten'], 'is_vegetarian'=>true, 'is_vegan'=>true, 'prep_time_minutes'=>20],

            // ── Desserts ───────────────────────────────
            ['name'=>'Classic Crème Brûlée',    'type'=>'dessert', 'price'=>7.50, 'description'=>'Traditional vanilla custard with a caramelised sugar crust and shortbread.', 'allergens'=>['dairy','eggs','gluten'], 'is_vegetarian'=>true, 'calories'=>380],
            ['name'=>'Dark Chocolate Fondant',  'type'=>'dessert', 'price'=>8.00, 'description'=>'Warm 70% chocolate fondant with salted caramel ice cream.', 'allergens'=>['gluten','dairy','eggs'], 'is_vegetarian'=>true, 'calories'=>450],
            ['name'=>'Seasonal Fruit Sorbet',   'type'=>'dessert', 'price'=>5.50, 'description'=>'Three scoops of house-made sorbet — ask your waiter for today\'s flavours.', 'allergens'=>[], 'is_vegetarian'=>true, 'is_vegan'=>true, 'is_gluten_free'=>true, 'calories'=>180],
            ['name'=>'Sticky Toffee Pudding',   'type'=>'dessert', 'price'=>7.00, 'description'=>'Classic British sponge with Medjool date sauce and clotted cream.', 'allergens'=>['gluten','dairy','eggs'], 'is_vegetarian'=>true],

            // ── Beverages ──────────────────────────────
            ['name'=>'Still Water (500ml)',     'type'=>'beverage', 'price'=>2.50, 'is_vegan'=>true, 'is_vegetarian'=>true, 'is_gluten_free'=>true, 'allergens'=>[], 'calories'=>0, 'prep_time_minutes'=>1],
            ['name'=>'Sparkling Water (500ml)', 'type'=>'beverage', 'price'=>2.50, 'is_vegan'=>true, 'is_vegetarian'=>true, 'is_gluten_free'=>true, 'allergens'=>[], 'calories'=>0, 'prep_time_minutes'=>1],
            ['name'=>'Freshly Brewed Coffee',   'type'=>'beverage', 'price'=>3.50, 'description'=>'Single origin Arabica, available as espresso, americano or flat white.', 'allergens'=>['dairy'], 'is_vegetarian'=>true, 'prep_time_minutes'=>4],
            ['name'=>'English Breakfast Tea',   'type'=>'beverage', 'price'=>3.00, 'allergens'=>['dairy'], 'is_vegetarian'=>true, 'prep_time_minutes'=>3],
            ['name'=>'Fresh Orange Juice',      'type'=>'beverage', 'price'=>4.50, 'description'=>'Freshly squeezed seasonal oranges.', 'allergens'=>[], 'is_vegetarian'=>true, 'is_vegan'=>true, 'is_gluten_free'=>true, 'calories'=>120, 'prep_time_minutes'=>5],
            ['name'=>'House Lemonade',           'type'=>'beverage', 'price'=>4.00, 'description'=>'Fresh lemon, mint and sparkling water.', 'allergens'=>[], 'is_vegetarian'=>true, 'is_vegan'=>true, 'is_gluten_free'=>true, 'calories'=>90, 'prep_time_minutes'=>4],
        ];

        foreach ($items as $data) {
            $factory->create($data);
        }
    }
}
