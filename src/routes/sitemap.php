<?php

use Illuminate\Support\Facades\Route;
use GIS\Sitemap\Http\Controllers\SitemapController;

$controller = config("sitemap.customSitemapController") ?? SitemapController::class;

Route::group([
    'middleware' => ['web'],
    'as' => 'web.sitemap.'
], function () use ($controller) {
    Route::get("sitemap.xml", [$controller, "index"])->name("index");
    Route::get("sitemap/map.xml", [$controller, "map"])->name("map");
    Route::get("sitemap/{model}.xml", [$controller, "model"])->name("model");
});

