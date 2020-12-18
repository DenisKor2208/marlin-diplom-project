<?php

/*паттерн Singleton
 * существует только один экземпляр объекта во всем приложении и его дубликатов не бывает
 */
class Database {

    private static $instance = null; //статичное приватное свойство
    private $pdo, $query, $error = false, $results, $count;

    private function __construct() { //так как этот метод/конструктор приватный, то доступ к нему возможен только в этом классе
        try {
            $this->pdo = new PDO('mysql:host=' . Config::get('mysql.host') . ';dbname=' . Config::get('mysql.database'), Config::get('mysql.username'), Config::get('mysql.password'));
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {        //если переменной не существует то создается класс, иначе возвращается созданный класс
            self::$instance = new Database(); //создание экземпляра/объекта класса и установка его в переменную $instance
        }
        return self::$instance;
    }

    public function query($sql, $params = []) {

        $this->error = false;
        $this->query = $this->pdo->prepare($sql);

        if (count($params)) {
            $i = 1;
            foreach ($params as $param) {
                $this->query->bindValue($i, $param); //параметр $i(должно быть число) распознает что вместо первого знака ? нужно подставить значение переменной $param
                $i++;
            }
        }

        if (!$this->query->execute()) {
            $this->error = true;
        }else{
            $this->results = $this->query->fetchAll(PDO::FETCH_OBJ); //FETCH_OBJ потому что мы используем ООП
            $this->count = $this->query->rowCount();
        }

        return $this;
    }

    public function error() { //(getter)возвращаем ошибки в sql запросе
        return $this->error;
    }

    public function results() { //(getter)возвращаем результаты из переменной $results так как свойство private
        return $this->results;
    }

    public function count() { //(getter)возвращаем кол-во затронутых в последнем запросе записей(записей которые мы получили в последнем запросе) так как свойство private
        return $this->count;
    }

    public function get($table, $where = []) { //второй аргумент типа массив и принимает только массив
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where = []) {
        return $this->action('DELETE', $table, $where);
    }

    public function action($action, $table, $where = []) {

        $operators = ['=', '>', '<', '>=', '<='];

        if(count($where) === 3) {
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if(in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql, [$value])->error()) { //true если нету ошибок
                    return $this;
                }
            }
        }
        return false;
    }

    public function insert($table, $fields = []) { //суть метода insert это подготовить запрос который уже передается методу query

        $values = '';
        foreach ($fields as $field) {
            $values .= "?,";
        }
        $values = rtrim($values, ',');

        $sql = "INSERT INTO {$table} (" . '`' . implode('`, `', array_keys($fields)) . '`' . ") VALUES ({$values})"; //особое внимание в этой строке стоит уделить backtick - `

        if(!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function update($table, $id, $fields = []) {

        $set = '';
        foreach($fields as $key => $field) {
            $set .= "{$key} = ?,"; // username = ?, password = ?,
        }

        $set = rtrim($set, ','); // username = ?, password = ?

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if(!$this->query($sql, $fields)->error()){
            return true;
        }

        return false;
    }

    public function first()
    {
        return $this->results()[0];
    }




}
?>