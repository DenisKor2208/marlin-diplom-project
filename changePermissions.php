<?php

require_once 'init.php';
$current_user = new User();

$transferred_userId = $_GET['id'];
$permission_user = $_GET['permission'];

if (Input::exists('get')) {
    if (is_numeric($transferred_userId) && $permission_user) {
        $current_user->changePermissions($transferred_userId, $permission_user);

        switch ($permission_user) {
            case 'standart':
                Session::flash('alert-info', 'Пользователь разжалован');
                break;
            case 'admin':
                Session::flash('alert-success', 'Пользователь назначен администратором');
                break;
        }
        Redirect::to('users/index.php');
    } else {
        Redirect::to('users/index.php');
    }
} else {
    Redirect::to('/index.php');
}


?>