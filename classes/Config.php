<?php

class Config {

    public static function get($path = null) {
        if ($path) { //если кто-то введет Config::get(); без параметров, то он вернет false
            $config = $GLOBALS['config']; //в переменную $config перекидываем массив из глобальной переменной $GLOBALS

            $path = explode('.', $path); //делим содержимое $path на массив

            foreach($path as $item) {
                if (isset($config[$item])) { //проверка "есть ли у меня в массиве $config ключ $item"
                    $config = $config[$item]; //если есть то в переменную $config помещаем значение данного массива(ключа); и так далее перебор в глубину массива $GLOBALS['config']
                }
            }
            return $config;
        }
        return false;
    }
}