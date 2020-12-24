<?php

class Validate {
    private $passed = false, $errors = [], $db = null;
    /* свойство $passed говорит о том, прошла ли валидация или нет
     * в свойстве $errors храним все ошибки
     * $db экземпляр подключения к базе
     */

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function check($source, $items = []) { //$items - 'username' с массивами значений
        foreach($items as $item => $rules) { //$items - 'username' с массивами - значениями; $item('username') => $rules(массив - значение ключа 'username')
            foreach ($rules as $rule => $rule_value) { //$rules(массив - значение ключа 'username') as $rule('required') => $rule_value('true')

                $value = $source[$item]; //$value = $_POST['username'] //получаем значение input из формы

                if ($rule == 'required' && empty($value)) { // если стоит правило 'required' и значение пустое
                    $this->addError("{$item} является обязательным!"); // то выводим ошибку
                } else if (!empty($value)) {
                    switch ($rule) { // в первой итерации $rule = 'required'
                        case 'min':
                            if (strlen($value) < $rule_value) { //strlen($value) - кол-во символов в значении $value
                                $this->addError("{$item} должен состоять минимум из {$rule_value} символов.");
                            }
                        break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} должен состоять максимум из {$rule_value} символов.");
                            }
                        break;
                        case 'matches':
                            if ($value != $source[$rule_value]) { // если $value (значение 'password_again' из массива $_POST) не равняется $rule_value(значение поля 'password' из массива $_POST)
                                $this->addError("{$rule_value} должен совпадать с {$item}"); // то выводим ошибку
                            }
                        break;
                        case 'unique':
                            $check = $this->db->get($rule_value, [$item, '=', $value]);// $rule_value - из какой таблицы в БД?; $item - поле username и в БД будут просматриваться значения поля username; $value - значение поля username из массива $_POST
                            if ($check->count()) { //проверка на кол-во результатов из БД по введенному в форму поля username значению
                                $this->addError("{$item} уже существует."); //если результатов будет больше нуля, то ошибка - значение не уникальное
                            }
                        break;
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) { //если не соответствует правилу валидации и возвращает false, до добавляем ошибку
                                $this->addError("{$item} не является электронной почтой.");
                            }
                        break;
                    }
                }
            }
        }

        if (empty($this->errors)) { //если ошибок нет,
            $this->passed = true;   // то валидация прошла успешно
        }

        return $this;
    }

    public function addError($error) { //записываем ошибки
        $this->errors[] = $error;
    }

    public function errors() {
        return $this->errors;
    }

    public function passed() { // вывод true или false исходя из того как прошла валидация
        return $this->passed;
    }
}