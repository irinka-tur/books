<?php
namespace Model;

class Coment {
    private $pageNumber;
    private $id;
    private $pagesCount = 1;
    private $data;
    
    private $select = 'select id, name, com  ' .
        'from coment order by id desc';
    private $insert = 'insert into coment (name, com) ' .
        'values (?,?)';
    private $updateSelect = 'select name, com' .
        'from coment where id=?';
  
    private $delete = 'delete from coment where id=?';
    private $count = 'select count(*) from coment';
    
    private $error = '';//ошибки валидации данных из формы
    
    public function __construct($pageNumber, $id)
    {
        $this->pageNumber = $pageNumber;
        $this->id = $id;
    }
    
    private function validate()
    {
        if (isset($_POST['name'])) {
            $name = trim($_POST['name']);
            $this->data['name'] = $name;
            if (mb_strlen($name) == 0) {
                $this->error .= 'Имя автора не может быть пустым<br>';
            }
        } else {
            $this->data['name'] = '';
            $this->error .= 'Необходимо заполнить имя автора<br>';
        }
        
        if (isset($_POST['com'])) {
            $com = trim($_POST['com']);
            $this->data['com'] = $com;
            if (mb_strlen($com) == 0) {
                $this->error .= 'Поле комментарий не может быть пустым<br>';
            }
        } else {
            $this->data['com'] = '';
            $this->error .= 'Необходимо написать комментарий<br>';
        }
  
        
        return $this->error == '';//true если не было ошибки
    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {//проверка данных и запись в базу
            if ($this->validate()) {
                \Core\Db::exec($this->insert, [$this->data['name'], $this->data['com'],]);
            }
        } else {//пустые данные для формы
            $this->data = ['id' => 0, 'name' => '', 'com' => '',];
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



