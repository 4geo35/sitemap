<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if($routes["routes"] && count($routes["routes"]))
        @foreach ($routes["routes"] as $map => $item)
            @if(empty($item->model))
                <url>
                    <loc>{{ route($item->name) }}</loc>
                    <lastmod>{{ $item->loaded_at }}</lastmod>
                    <priority>1.0</priority>
                </url>
            @else
                <url>
                    <loc>{{ $item->uri }}</loc>
                    <lastmod>{{ $item->loaded_at }}</lastmod>
                    <priority>1.0</priority>
                </url>
            @endif
        @endforeach
    @endif
</urlset>