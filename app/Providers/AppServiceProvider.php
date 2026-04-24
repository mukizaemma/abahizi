<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\ProductStoryPoint;
use App\Models\ProductStorySetting;
use App\Models\Program;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }


    public function boot()
    {
        View::share('setting', Setting::firstOrEmpty());
        View::share(
            'ourPrograms',
            Schema::hasTable('programs')
                ? Program::query()->oldest()->get()
                : collect()
        );
        View::share(
            'menuServices',
            Schema::hasTable('services')
                ? Service::query()->active()->orderBy('sort_order')->orderBy('title')->get()
                : collect()
        );

        View::composer(['frontend.our-products', 'frontend.product-detail'], function ($view) {
            if (! Schema::hasTable('product_story_points')) {
                $view->with([
                    'productStoryHeading' => null,
                    'productStoryPoints' => collect(),
                ]);

                return;
            }

            $heading = null;
            if (Schema::hasTable('product_story_settings')) {
                $row = ProductStorySetting::query()->first();
                $heading = $row?->heading;
            }

            $points = ProductStoryPoint::query()->active()->ordered()->get();

            $view->with([
                'productStoryHeading' => $heading,
                'productStoryPoints' => $points,
            ]);
        });
    }
}
