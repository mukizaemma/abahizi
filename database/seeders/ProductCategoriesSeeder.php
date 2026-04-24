<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Women', 'Men', 'Bags', 'Accessories', 'Other'];

        $order = 0;
        foreach ($names as $name) {
            $slug = Str::slug($name);
            ProductCategory::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'sort_order' => $order++,
                    'is_active' => true,
                ]
            );
        }
    }
}
