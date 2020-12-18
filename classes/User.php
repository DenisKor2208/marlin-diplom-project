<?php

class User { //для регистрации пользователя
    private $db, $data, $session_name, $isLoggedIn, $cookieName;
// данные текущего пользователя хранятся в переменной $data

    public function __construct($user = null) {
        $this->db = Database::getInstance();
        $this->session_name = Config::get('session.user_session');
        $this->cookieName = Config::get('cookie.cookie_name');

        if (!$user) { //Если $user(передается id) пустой, то выполняется код; получаем текущего залогиненного пользователя
            if (Session::exists($this->session_name)) { //проверка есть ли запись в сессии
                $user = Session::get($this->session_name); //получаем из сессии id залогиненного пользователя

                if ($this->find($user)) {
                    $this->isLoggedIn = true;
                }
            }
        } else { //если вы передаем id(пользователя), то в таком случае мы его должны только найти
            $this->find($user);
        }
    }

    public function create($fields = []) {
        $this->db->insert('users', $fields);
    }

    public function login($email = null, $password = null, $remember = false) {

        if (!$email && !$password && $this->exists()) { //если мы не передали email и пароль и текущий пользователь существует
            Session::put($this->session_name, $this->data()->id); //то просто записываем сессию текущему пользователю
        } else {
            $user = $this->find($email); //вытаскиваем пользователя(записываем пользователя в переменную $this->data)
            if ($user) {
                if (password_verify($password, $this->data()->password)) { //если переданный пароль соответствует тому который имеется в БД
                    Session::put($this->session_name, $this->data()->id); //то записываем в сессию id пользователя

                    if ($remember) { //выполнение логики по нажатии на кнопку 'Remember me'
                        $hash = hash('sha256', uniqid()); //генерируется значение hash

                        $hashCheck = $this->db->get('user_sessions', ['user_id', '=', $this->data()->id]); //пытаемся найти в БД текущий hash пользователя

                        if (!$hashCheck->count()) { //если нет найденной записи
                            $this->db->insert('user_sessions', [
                                'user_id' => $this->data()->id, //id пользователя
                                'hash' => $hash //сгенерированный нами hash
                            ]);
                        } else {
                            $hash = $hashCheck->first()->hash;
                        }
                        Cookie::put($this->cookieName, $hash, Config::get('cookie.cookie_expiry')); //записываем сгенерированный $hash в cookie для пользователя
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function find($value = null) { //проверка на существование переданного id или email в базе // записываем данные пользователя в переменную $data
        if (is_numeric($value)){ //если значение цифровое, то значит это id
            $this->data = $this->db->get('users', ['id', '=', $value])->first(); //записываем найденную запись по переданному id в переменную data
        } else { //иначе это email
            $this->data = $this->db->get('users', ['email', '=', $value])->first(); //записываем найденную запись по переданному email в переменную data
        }
            if ($this->data) { //возвращаем true если что-то найдено и записалось
                return true;
            }
        return false;
    }

    public function data() { //getter для переменной data
        return $this->data;
    }

    public function isLoggedIn() { //геттер для переменной isLoggedIn
        return $this->isLoggedIn;
    }

    public function logout() {
        $this->db->delete('user_sessions', ['user_id', '=', $this->data()->id]); //удаляем cookie из БД
        Session::delete($this->session_name); //удаляем сессию пользователя
        Cookie::delete($this->cookieName); //устанавливаем отрицательное время жизни cookie у пользователя
    }

    public function exists() { //проверка, существует ли текущий пользователь у нас в БД
        return (!empty($this->data())) ? true : false;
    }

    public function update($fields = [], $id = null) {

        if (!$id && $this->isLoggedIn()) { //если переданный id null и пользователь залогинен
            $id = $this->data()->id;
        }

        $this->db->update('users', $id, $fields);
    }

    public function hasPermissions($key = null) { //работа с ролями и правами
        if ($key) { //если $key имеет в себе значение
            $group = $this->db->get('groups_users', ['id', '=', $this->data()->group_id]); //проверяем имеется ли в таблице group в поле id значение group_id текущего пользователя из таблицы users и выдергиваем найденную строчку

            if ($group->count()) { //проверяем было ли найдено что либо в таблице group
                $permissions = $group->first()->permissions; //выхватываем из найденной строчки значение поля permissions из таблицы groups
                $permissions = json_decode($permissions, true); //декодируем данные json в ассоциативный массив

                if ($permissions[$key]) { //если переданная нами роль найдена в поле permissions, то возвращаем true
                    return true;
                }
            }
        }
        return false;
    }
}