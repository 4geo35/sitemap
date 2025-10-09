<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ route('web.sitemap.map') }}</loc>
        <lastmod></lastmod>
    </sitemap>

    @if($routes)
        @foreach($routes as $name => $item)
            @if ($item->model)
                <sitemap>
                    <loc>{{ route('web.sitemap.model', ['model' => $name]) }}</loc>
                    <lastmod></lastmod>
                    <changefreq>daily</changefreq>
                </sitemap>
            @endif
        @endforeach
    @endif
</sitemapindex>
