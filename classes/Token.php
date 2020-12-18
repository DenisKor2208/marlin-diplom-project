<?php

class Token {
    public static function generate() { //создание нового токена и запись его в сессию с нужным нам именем
        return Session::put(Config::get('session.token_name'), md5(uniqid()));
    }

    public static function check($token) { //проверка на существование такого ключа в сессии и проверка переданного токена со значением в сессии
        $tokenName = Config::get('session.token_name');

        if(Session::exists($tokenName) && $token == Session::get($tokenName)) {
            Session::delete($tokenName); //удаление токена из сессии
            return true;
        }
        return false;
    }
}