<?php

namespace GIS\Sitemap;
use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        // config publish
        $this->publishes([
            __DIR__.'/config/sitemap.php' => config_path('sitemap.php'),
        ]);

        // Views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'sitemap');
    }

    public function register(): void
    {
        // Config
        $this->mergeConfigFrom(__DIR__ . "/config/sitemap.php", "sitemap");

        // Routes
        $this->loadRoutesFrom(__DIR__.'/routes/sitemap.php');
    }

}
