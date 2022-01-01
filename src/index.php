<?php

namespace App;

function load_classphp($directory) {
    if(is_dir($directory)) {
        $scan = scandir($directory);
        unset($scan[0], $scan[1]); //unset . and ..
        foreach($scan as $file) {
            if(is_dir($directory."/".$file)) {
                load_classphp($directory."/".$file);
            } else {
                if(strpos($file, '.php') !== false) {
                    include_once($directory."/".$file);
                }
            }
        }
    }
}

load_classphp('./');

session_start();

$route_map = new Kernel\RouteMap();
// Admin
$route_map->addRouteMap("/studentcreate", Controller\AdminController::class, "studentcreate");
$route_map->addRouteMap("/studentmanage", Controller\AdminController::class, "studentManage");

$route_map->addRouteMap("/migrate", Controller\AdminController::class, "migrate");

$router = new Kernel\Router();
$router->set404("<center><span style='font-size:24px;'>404 NOT FOUND</span></center>");
$router->set401("<center><span style='font-size:24px;'>401 UNAUTHORIZED</span></center>");
$router->solve($_SERVER['REQUEST_URI'], $route_map);
?>