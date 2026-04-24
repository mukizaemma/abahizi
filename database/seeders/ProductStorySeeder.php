<?php

namespace Database\Seeders;

use App\Models\ProductStoryPoint;
use App\Models\ProductStorySetting;
use Illuminate\Database\Seeder;

class ProductStorySeeder extends Seeder
{
    public function run(): void
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('product_story_settings')) {
            return;
        }

        if (! ProductStorySetting::query()->exists()) {
            ProductStorySetting::create([
                'heading' => 'What goes into our handbags',
            ]);
        }

        if (! \Illuminate\Support\Facades\Schema::hasTable('product_story_points')) {
            return;
        }

        if (ProductStoryPoint::query()->exists()) {
            return;
        }

        $paragraphs = [
            'Abahizi Rwanda employs over 268 local full-time artisans, 90% of which are women, and 77% are parents. The majority of the workforce are mothers, daughters, sisters, and have worked at Abahizi Rwanda for over three years.',
            'Our products are hand made by exceptionally skilled artisans. This makes every Abahizi Rwanda product a hand-crafted treasure. Each of our bags represents the skills of a gifted artisan in Masoro, Rwanda.',
            'We provide all of our artisans with full benefits. Employees receive health insurance for themselves and their entire families, paid maternity leave, leave, sick days, and vacation days.',
            'Other wide range support includes life skills training programs that equip employees with health education, financial literacy, counseling, leadership training, and English classes.',
            'Our social enterprise approach to business creates economic and social good for everyone we touch: our business, our employees, and our community.',
        ];

        foreach ($paragraphs as $i => $text) {
            ProductStoryPoint::create([
                'content' => $text,
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }
    }
}
