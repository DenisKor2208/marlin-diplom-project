<?php
require_once '../init.php';

$current_user = new User();

if (!$current_user->hasPermissions('admin')) {
    Redirect::to('../index.php');
}

$transferred_userId = $_GET['id'];
$permission_user = $_GET['permission'];

if (Input::exists('get')) {
    if (is_numeric($transferred_userId) && $permission_user) {
        $result = $current_user->changePermissions($transferred_userId, $permission_user);

        if ($result) {
            switch ($permission_user) {
                case 'standart':
                    Session::flash('alert-info', 'Пользователь разжалован');
                    break;
                case 'admin':
                    Session::flash('alert-success', 'Пользователь назначен администратором');
                    break;
            }
            Redirect::to('index.php');
        } else {
            Session::flash('alert-danger', 'Не удалось изменить права пользователя');
            Redirect::to('index.php');
        }
    } else {
        Session::flash('alert-info', 'ID пользователя и права имеют неверный формат');
        Redirect::to('index.php');
    }
} else {
    Redirect::to('index.php');
}
?>