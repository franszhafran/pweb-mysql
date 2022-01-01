<?php
namespace App\Kernel;

class RouteMap {
    private $route_map;

    public function __construct() {
        $this->route_map = [];
    }

    public function getRouteMap() {
        return $this->route_map;
    }

    public function addRouteMap(string $url, string $controller, string $function) {
        $this->route_map[] = [$url, $controller, $function];
    }
}
?>