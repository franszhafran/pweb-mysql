<?php
namespace App\Kernel;

class Request {
    public static function init(): self {
        $r = new self;
        foreach($_POST as $key=>$value) {
            $r->$key = $value;
        }

        $get = new \stdClass();
        foreach($_GET as $key=>$value) {
            $get->$key = $value;
        }
        $r->get = $get;
        
        return $r;
    }
}
?>