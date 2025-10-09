### Установка
Заполните конфиг (модели проекта, модели пакетов, исключения роутов) и время жизни кэша

        php artisan vendor:publish --provider="GIS\Sitemap\SitemapServiceProvider" --tag=config

Карта будет доступна по адресу /sitemap.xml