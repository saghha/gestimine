<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Filters\DecodeSlugFilter;
use Sanitizer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanitizer::extend('decode_slug', DecodeSlugFilter::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
