<?php

return [
    "lifetime" => 43200,
    "customSitemapController" => null,
    "exclude" => [
        "login",
        "thumb-img",
        "web.privacy-policy",
        "web.sitemap.model",
        "web.sitemap.map",
        "web.sitemap.index",
    ],
    'models' => [
        'web.articles.show' => '\GIS\ArticlePages\Models\Article',
        'web.service-categories.show' => '\GIS\ServiceCatalog\Models\ServiceCategory',
        'web.services.show' => '\GIS\ServiceCatalog\Models\Service',
    ],
];