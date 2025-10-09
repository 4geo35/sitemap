<?php

namespace GIS\Sitemap\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    /**
     * List of sitemaps
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $routes = $this->getRoutes("show");
        return response()
            ->view("sitemap::web.sitemap.index",compact("routes"))
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Map simple pages
     *
     * @return \Illuminate\Http\Response
     */
    public function map(){
        $routes = $this->getRoutes();
        return response()
            ->view("sitemap::web.sitemap.show",compact("routes"))
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Map models
     *
     * @param $model
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function model($model){
        $all = $this->getRoutes("show");
        if (empty($all) || !isset($all["routes"][$model]))  return redirect(route("web.sitemap.index"));
        $routes = $this->getModelRoutes($all["routes"][$model]);

        return response()
            ->view("sitemap::web.sitemap.show", compact("routes"))
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Web routes exclude config
     *
     * @return array
     */
    protected function getRoutes($type = "map"){
        return Cache::remember("sitemap-get-routes:".$type, config('sitemap.lifetime', 0), function () use ($type){
            $routes = Route::getRoutes();
            $array = [];
            $arrayShow = [];
            foreach ($routes->getRoutesByName() as $name => $route) {
                if (
                    in_array("GET", $route->methods) &&
                    ! in_array($name, config("sitemap.exclude",[])) &&
                    (
                        (strstr($name, '.') === FALSE) ||
                        (strstr($name, 'web.') !== FALSE) ||
                        (strstr($name, '.page.') !== FALSE)
                    )
                ) {
                    $uri = $route->uri();
                    $modelName = false;
                    $param = false;
                    if ($show = strstr(str_ireplace("web.", "",$name),".show",true)){
                        $modelName = $this->getModelName($show, $name);
                        $params = $route->parameterNames();
                        $param = isset($params[0]) ? $params[0] : false;
                    }

                    $obj = (object)[
                        "name"=> $name,
                        "uri"=> $uri,
                        "model" => $modelName,
                        "param" => $param,
                        "loaded_at" => date('Y-m-d')
                    ];

                    if ($show) $arrayShow[$show] = $obj;
                    else $array[$uri."-list"] = $obj;
                }
            }
            return ["routes" => $type == "show" ? $arrayShow : $array, "date"=>date('Y-m-d')];
        });
    }

    /**
     * Get model name 
     *
     * @param $str
     * @param $name
     * @return false|mixed|string
     */
    protected function getModelName($str, $name){
        $modelName = false;
        $singular = Str::singular($str);
        $singular = str_replace("_"," ", $singular);
        $singular = ucwords($singular);
        $singular = str_replace(" ","", $singular);
        $appClassName = "\App\Models\/".$singular;
        if (class_exists($appClassName)) $modelName = $appClassName;
        if (! $modelName)  $modelName = config("sitemap.models")[$name] ? config("sitemap.models")[$name]: false;
        return $modelName;
    }

    /**
     * Get Model's routes
     *
     * @param $routeObject
     * @return array
     */
    protected function getModelRoutes($routeObject){
        return Cache::remember("sitemap-get-model-routes:".$routeObject->name, config('sitemap.lifetime', 0), function () use ($routeObject){
            $class = $routeObject->model;
            $routes = [];
            if (class_exists($class)) {
                $query = $class::query()->select();
                $models = $query->get();
                foreach ($models as $model){
                    if ($model->published_at)
                        $routes[] = (Object)[
                            "name"=> $routeObject->name,
                            "uri"=> route( $routeObject->name,[$routeObject->param => $model]),
                            "model" => $routeObject->model,
                            "param" => $routeObject->param,
                            "loaded_at" => date('Y-m-d', $model->updated_at->timestamp)
                        ];
                }
            }
            return ["routes" => $routes];
        });
    }
}
