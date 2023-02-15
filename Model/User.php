<?php
namespace Model;

class User {
    private $pageNumber;
    private $id;
    private $pagesCount = 1;
    private $data;
    
    private $select = 'select id, login, password, type_lit ' .
        'from user order by login';
    private $insert = 'insert into user (login, password, type_lit) ' .
        'values (?,?,?)';
    private $updateSelect = 'select login, password, type_lit ' .
        'from user where id=?';
    private $update = 'update user set login=?, password=?, type_lit=? ' .
        'where id=?';
    private $delete = 'delete from user where id=?';
    private $count = 'select count(*) from user';
    
    private $error = '';//ошибки валидации данных из формы
    
    public function __construct($pageNumber, $id)
    {
        $this->pageNumber = $pageNumber;
        $this->id = $id;
    }
    
    private function validate()
    {
        if (isset($_POST['login'])) {
            $login = trim($_POST['login']);
            $this->data['login'] = $login;
            if (mb_strlen($login) == 0) {
                $this->error .= 'Логин не может быть пустым<br>';
            }
        } else {
            $this->data['login'] = '';
            $this->error .= 'Необходимо заполнить логин<br>';
        }
        if (isset($_POST['password'])) {
            $password = trim($_POST['password']);
            $this->data['password'] = $password;
            if (mb_strlen($password) == 0) {
                $this->error .= 'Пароль не может быть пустым<br>';
            }
        } else {
            $this->data['password'] = '';
            $this->error .= 'Необходимо заполнить пароль<br>';
        }
        if (isset($_POST['type_lit'])) {
            $type_lit = trim($_POST['type_lit']);
            $this->data['type_lit'] = $type_lit;
            if ($type_lit != 'роман' && $type_lit != 'детектив' && $type_lit != 'фантастика'&& $type_lit != 'научная литература') {
                $this->error .= 'Выберите только один вид литературы<br>';
            }
        } else {
            $this->data['type_lit'] = '';
            $this->error .= 'Необходимо выбрать жанр ваших любимых книг<br>';
        }
        return $this->error == '';//true если не было ошибки
    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {//проверка данных и запись в базу
            if ($this->validate()) {
                \Core\Db::exec($this->insert, [$this->data['login'], $this->data['password'], $this->data['type_lit']]);
            }
        } else {//пустые данные для формы
            $this->data = ['id' => 0, 'login' => '', 'password' => '', 'type_lit' => ''];
        }
    }
    
    public function read()
    {
        $this->pagesCount = ceil(\Core\Db::count($this->count) 
                / DATA_PER_PAGE);
        if ($this->pagesCount < 1) $this->pagesCount = 1;
        $first = ($this->pageNumber - 1) * DATA_PER_PAGE;
        $sql = $this->select . ' limit ' . $first . ',' . DATA_PER_PAGE;
        $this->data = \Core\Db::select($sql);
    }
    
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {//проверка данных и запись в базу
            if ($this->validate()) {
                \Core\Db::exec($this->update, [$this->data['login'], $this->data['password'], $this->data['type_lit'], $this->id]);
            }
        } else {//Чтение данных для редактирования
            $data = \Core\Db::select($this->updateSelect, [$this->id]);
            $this->data = $data[0];
        }
    }
    
    public function delete()
    {
        \Core\Db::exec($this->delete, [$this->id]);
    }
    
    public function getPageNumber() {
        return $this->pageNumber;
    }

    public function getId() {
        return $this->id;
    }

    public function getPagesCount() {
        return $this->pagesCount;
    }

    public function getData() {
        return $this->data;
    }

    public function getError() {
        return $this->error;
    }
}


