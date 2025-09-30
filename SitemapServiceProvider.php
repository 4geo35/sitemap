<?php

namespace GIS\Sitemap;
use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{

    public function boot(): void
    {

    }

    public function register(): void
    {
        // Config
        $this->mergeConfigFrom(__DIR__ . "/config/sitemap.php", "sitemap");

    }

}
