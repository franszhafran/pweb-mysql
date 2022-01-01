<?php
namespace App\Kernel;

class Router {
    public function solve(string $url, RouteMap $route_map) {
        $routes = $route_map->getRouteMap();
        foreach($routes as $route) {
            if($url == $route[0]) {
                $resolver = new $route[1]();
                return $resolver->{$route[2]}();
            }
        }
        if($url == '/401') {
            echo $this->html401;
            return;
        } 
        echo $this->html404;
    }

    public function set404($html) {
        $this->html404 = $html;
    }

    public function set401($html) {
        $this->html401 = $html;
    }
}
?>