<?php

session_start();

require_once "classes/Database.php";
require_once "classes/Config.php";
require_once "classes/Validate.php";
require_once "classes/Input.php";
require_once "classes/Token.php";
require_once "classes/Session.php";
require_once "classes/User.php";
require_once "classes/Redirect.php";
require_once "classes/Cookie.php";
require_once "functions.php";


$GLOBALS['config'] = [ /* Глобальный массив конфигураций для всего приложения */
    'mysql' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'marlin_diplom_project'
    ],

    'session' => [
        'token_name' => 'token', //в сессии значение ключа мы будем хранить под именем token
        'user_session' => 'user'
    ],

    'cookie' => [
        'cookie_name' => 'hash', //в сессии значение ключа мы будем хранить под именем token
        'cookie_expiry' => 604800 //срок хранения cookie
    ]
];


if (Cookie::exists(Config::get('cookie.cookie_name')) && !Session::exists(Config::get('session.user_session'))) { //если есть cookie, но нет сессии
    $hash = Cookie::get(Config::get('cookie.cookie_name')); //получаем значение текущего cookie имеющегося у клиента
    $hashCheck = Database::getInstance()->get('user_sessions', ['hash', '=', $hash]); //находим в БД строчку по полученному значению cookie

    if ($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login(); //логиним пользователя без логина и пароля
    }
}
