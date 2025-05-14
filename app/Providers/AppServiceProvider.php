<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use App\Models\Banner;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::share('lang', request()->route('lang') ?? 'en');
        View::composer('*', function ($view) {
            $banners = Banner::available()
                ->where('start_date', '<=', now())
                ->orderBy('created_at', 'desc')
                ->get();

            $view->with('banners', $banners);
        });

        // Add this conditional banner loading
        try {
            if (Schema::hasTable('banners')) {
                $activeBanners = Banner::where('is_active', 1)
                    ->where('expiration_date', '>=', now())
                    ->orderBy('created_at', 'desc')
                    ->get();
                View::share('activeBanners', $activeBanners);
            } else {
                View::share('activeBanners', collect([]));
            }
        } catch (QueryException $e) {
            // If there's an error, just provide an empty collection
            View::share('activeBanners', collect([]));
        }
        View::composer(['welcome', 'layouts.app'], function ($view) {
        $banners = Banner::available()
            ->where('start_date', '<=', now())
            ->orderBy('created_at', 'desc')
            ->get();
        
        $view->with('banners', $banners);
    });
    }
}
