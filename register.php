<?php

require_once 'init.php';

/*Данный код отвечает за валидацию и использование двух компонентов Input и Validation*/
if (Input::exists()) { // exists - проверка была ли отправлена форма
    if (Token::check(Input::get('token'))) { //проверка что наш токен который мы передали с данными формы является именно тем, который находится в сессии у данного пользователя
        $validate = new Validate();

        $validation = $validate->check($_POST, [ // 1. что чекать($_POST) - источник информации  2. На что чекать
            'username' => [
                'required' => true,
                'min' => 2,
                'max' => 15
            ],
            'email' => [ //здесь email название поля в форме (тег input -> name)
                'required' => true,
                'email' => true, //здесь email название правила валидации которые прописываются в классе Validate
                'unique' => 'users' //email должен быть уникальным в таблице users
            ],
            'password' => [
                'required' => true,
                'min' => 3
            ],
            'password_again' => [
                'required' => true,
                'matches' => 'password' //должен совпадать со значением поля password
            ]
        ]);

        if ($validation->passed()) {

            $agreeRules = (Input::get('agree_rules')) === 'on' ? true : false;

                if ($agreeRules) {
                    //Database
                    $user = new User();
                    $user->create([ //запись валидных данных в таблицу
                        'username' => Input::get('username'),
                        'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT),
                        'email' => Input::get('email'),
                        'data_register_user' => date("d/m/Y")
                    ]);

                    Session::flash('success', 'Регистрация прошла успешно!'); //записываем значение(2 аргумент) в ключ сессии(1 аргумент)
                    //Redirect::to('index.php');
                } else {
                    Session::flash('alert-info', 'Необходимо согласие с условиями!');
                }
            } /*else {
            foreach ($validation->errors() as $error) {
                echo $error . "<br>";
            }
        }*/
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register</title>
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form action="" method="post" class="form-signin">
        <a href="index.php"><img class="mb-4" src="images/apple-touch-icon.png" alt="" width="72" height="72"></a>
    	  <h1 class="h3 mb-3 font-weight-normal">Регистрация</h1>
<!--
        <div class="alert alert-danger">
          <ul>
            <li>Ошибка валидации 1</li>
            <li>Ошибка валидации 2</li>
            <li>Ошибка валидации 3</li>
          </ul>
        </div>

        <div class="alert alert-success">
          Успешный успех
        </div>

        <div class="alert alert-info">
          Информация
        </div>
-->
        <?php
            if (Input::exists()) {
                if (Session::exists('success')) {
                    echo '<div class="alert alert-success">' . Session::flash('success') . '</div>';
                }

                if (!$validation->passed()) {
                    echo '<div class="alert alert-danger"><ul>';
                    foreach ($validation->errors() as $error) {
                        echo '<li>' . $error . '</li>';
                    }
                    echo '</ul></div>';
                }

                if (Session::exists('alert-info')) {
                    echo '<div class="alert alert-info">' . Session::flash('alert-info') . '</div>';
                }
            }
        ?>

    	<div class="form-group">
          <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="<?php echo Input::get('email')?>">
        </div>

        <div class="form-group">
          <input type="text" name="username" class="form-control" id="username" placeholder="Ваше имя" value="<?php echo Input::get('username')?>">
        </div>

        <div class="form-group">
          <input type="password" name="password" class="form-control" id="password" placeholder="Пароль">
        </div>
        
        <div class="form-group">
          <input type="password" name="password_again" class="form-control" id="password_again" placeholder="Повторите пароль">
        </div>

        <div class="checkbox mb-3">
            <label>
    	      <input type="checkbox" name="agree_rules" id="agree_rules"> Согласен со всеми правилами
    	    </label>
        </div>

        <input type="hidden" name="token" value="<?php echo Token::generate();?>">
    	<button class="btn btn-lg btn-primary btn-block" type="submit">Зарегистрироваться</button>

        <p class="mt-5 mb-3 text-muted">&copy; 2017-2020</p>
    </form>
</body>
</html>
