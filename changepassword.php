<?php
require_once 'init.php';

$current_user = new User;

$validate = new Validate();
$validate->check($_POST, [ //проверяем глобальный массив POST
    'current_password' => ['required' => true, 'min' => 6],
    'new_password' => ['required' => true, 'min' => 6],
    'new_password_again' => ['required' => true, 'min' => 6, 'matches' => 'new_password'] //'matches' => 'new_password' - поле должно совпадать с new_password
]);

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        if ($validate->passed()) {
            if (password_verify(Input::get('current_password'), $current_user->data()->password)) {
                $current_user->update(['password' => password_hash(Input::get('new_password'), PASSWORD_DEFAULT)]);
                Session::flash('alert-success', 'Пароль обновлен');
                Redirect::to('changepassword.php');
                die();
            } else {
                Session::flash('alert-info', 'Текущий пароль недействителен');
                Redirect::to('changepassword.php');
                die();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">User Management</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Главная</a>
            </li>

            <?php  if ($current_user->hasPermissions('admin')) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="users/index.php">Управление пользователями</a>
                </li>
            <?php endif; ?>

        </ul>

          <?php if($current_user->isLoggedIn()) :?>
              <ul class="navbar-nav">
                  <li class="nav-item">
                      <a href="profile.php" class="nav-link">Профиль</a>
                  </li>
                  <li class="nav-item">
                      <a href="logout.php" class="nav-link">Выйти</a>
                  </li>
              </ul>
          <?php else :?>
              <ul class="navbar-nav">
                  <li class="nav-item">
                      <a href="login.php" class="nav-link">Войти</a>
                  </li>
                  <li class="nav-item">
                      <a href="register.php" class="nav-link">Регистрация</a>
                  </li>
              </ul>
          <?php endif; ?>

      </div>
    </nav>
   <div class="container">
     <div class="row">
       <div class="col-md-8">
         <h1>Изменить пароль</h1>

           <?php
           if (Session::exists('alert-success')) {
               echo '<div class="alert alert-success">' . Session::flash('alert-success') . '</div>';
           }
           if (Session::exists('alert-info')) {
               echo '<div class="alert alert-info">' . Session::flash('alert-info') . '</div>';
           }

           if (Input::exists()) {
               if (!$validate->passed()) {
                   echo '<div class="alert alert-danger"><ul>';
                   foreach ($validate->errors() as $error) {
                       echo '<li>' . $error . '</li>';
                   }
                   echo '</ul></div>';
               }
           }
           ?>

         <ul>
           <li><a href="profile.php">Изменить профиль</a></li>
         </ul>

         <form action="" method="post" class="form">
           <div class="form-group">
             <label for="current_password">Текущий пароль</label>
             <input type="password" name="current_password" id="current_password" class="form-control">
           </div>
           <div class="form-group">
             <label for="new_password">Новый пароль</label>
             <input type="password" name="new_password" id="new_password" class="form-control">
           </div>
           <div class="form-group">
             <label for="new_password_again">Повторите новый пароль</label>
             <input type="password" name="new_password_again" id="new_password_again" class="form-control">
           </div>
             <input type="hidden" name="token" id="token" value="<?php echo Token::generate();?>">

           <div class="form-group">
             <button class="btn btn-warning">Изменить</button>
           </div>
         </form>
       </div>
     </div>
   </div>
</body>
</html>