<?php

class Redirect {

    public static function to($location = null) {
        if($location) {
            if(is_numeric($location)) { //если переменная не пустая и значение в ней типа integer
                switch($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found.');
                        include 'includes/errors/404.php';
                        exit;
                    break;
                }
            }
            header('Location:' . $location); //иначе redirect по указанному пути
        }
    }
}