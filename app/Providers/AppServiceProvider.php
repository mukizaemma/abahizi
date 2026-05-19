<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\ProductStoryPoint;
use App\Models\ProductStorySetting;
use App\Models\Program;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    private function databaseAvailable(): bool
    {
        try {
            DB::connection()->getPdo();
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    public function boot()
    {
        $dbReady = $this->databaseAvailable();

        View::share(
            'setting',
            $dbReady && Schema::hasTable('settings')
                ? Setting::firstOrEmpty()
                : new Setting()
        );
        $programs = $dbReady && Schema::hasTable('programs')
            ? Program::query()->oldest()->get()
            : collect();

        View::share('ourPrograms', $programs);
        View::share('navProgramWhatWeDo', $programs->get(0));
        View::share('navProgramOurImpact', $programs->get(1));
        View::share(
            'menuServices',
            $dbReady && Schema::hasTable('services')
                ? Service::query()->active()->orderBy('sort_order')->orderBy('title')->get()
                : collect()
        );

        View::composer(['frontend.our-products', 'frontend.product-detail'], function ($view) use ($dbReady) {
            if (! $dbReady || ! Schema::hasTable('product_story_points')) {
                $view->with([
                    'productStoryHeading' => null,
                    'productStoryPoints' => collect(),
                ]);

                return;
            }

            $heading = null;
            if ($dbReady && Schema::hasTable('product_story_settings')) {
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
