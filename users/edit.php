<?php
require_once '../init.php';

if (is_numeric($_GET['id'])) {
    $viewed_user = new User($_GET['id']);
} else {
    Redirect::to('index.php');
}

$current_user = new User;

if (!$current_user->hasPermissions('admin')) {
    Redirect::to('../index.php');
}

$validate = new Validate();
$validate->check($_POST, [ //проверяем глобальный массив POST
    'username' => ['required' => true, 'min' => 2], //username должен быть обязательным для заполнения и минимум 2 символа в длинну
    'status_user' => ['required' => true, 'min' => 1]
]);

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        if ($validate->passed()) {
            $viewed_user->update(['username' => Input::get('username'), 'status_user' => Input::get('status_user')], $viewed_user->data()->id);
            Session::flash('alert-success', 'Профиль обновлен');
            Redirect::to("edit.php?id=" . $viewed_user->data()->id);
            die();
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
              <a class="nav-link" href="/index.php">Главная</a>
            </li>

              <?php  if ($current_user->hasPermissions('admin')) : ?>
                  <li class="nav-item">
                      <a class="nav-link" href="index.php">Управление пользователями</a>
                  </li>
              <?php endif; ?>

          </ul>

            <?php if($current_user->isLoggedIn()) :?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="/profile.php" class="nav-link">Профиль</a>
                    </li>
                    <li class="nav-item">
                        <a href="/logout.php" class="nav-link">Выйти</a>
                    </li>
                </ul>
            <?php else :?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="/login.php" class="nav-link">Войти</a>
                    </li>
                    <li class="nav-item">
                        <a href="/register.php" class="nav-link">Регистрация</a>
                    </li>
                </ul>
            <?php endif; ?>

        </div>
    </nav>

   <div class="container">
     <div class="row">
       <div class="col-md-8">
         <h1>Профиль пользователя - <?php echo $viewed_user->data()->username; ?></h1>

           <?php
           if (Session::exists('alert-success')) {
               echo '<div class="alert alert-success">' . Session::flash('alert-success') . '</div>';
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

         <form action="" method="post" class="form">
           <div class="form-group">
             <label for="username">Имя</label>
             <input type="text" id="username" name="username" class="form-control" value="<?php echo $viewed_user->data()->username; ?>">
           </div>
           <div class="form-group">
             <label for="status_user">Статус</label>
             <input type="text" id="status_user" name="status_user" class="form-control" value="<?php echo $viewed_user->data()->status_user; ?>">
           </div>
             <input type="hidden" name="token" id="token" value="<?php echo Token::generate();?>">

           <div class="form-group">
             <button class="btn btn-warning">Обновить</button>
           </div>
         </form>


       </div>
     </div>
   </div>
</body>
</html>