<?php

require_once '../init.php';
$current_user = new User();

if (Input::exists('get')) {
    if (is_numeric($_GET['id'])) {
        $transferred_userId = $_GET['id'];
    } else {
        Redirect::to('index.php');
    }
    $current_user->deleteUser($transferred_userId);
    Session::flash('alert-info', 'Пользователь удален');
    Redirect::to('index.php');
} else {
    Redirect::to('index.php');
}

?>