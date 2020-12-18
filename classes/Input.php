<?php

class Input {
    public static function exists($type = 'post') { //проверяет пустой ли глобальный массив GET или POST

        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
            case 'get':
                return (!empty($_GET)) ? true : false;
            default:
                return false;
            break;
        }
    }

    public static function get($item) {

        if (isset($_POST[$item])) { //проверка существует ли переданный ключ $item в глобальном массиве POST
            return $_POST[$item]; //если есть, то возвращаем значение ключа $item массива POST
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        }

        return '';
    }
}


