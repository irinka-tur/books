<?php
namespace Model;

class Bookshelf {
    private $pageNumber;
    private $id;
    private $pagesCount = 1;
    private $data;
    
    private $select = 'select id, fio, book_name, year, price  ' .
        'from bookshelf order by id desc';
    private $insert = 'insert into bookshelf (fio, book_name,year, price) ' .
        'values (?,?,?,?)';
    private $updateSelect = 'select book_name, fio, year,price' .
        'from bookshelf where id=?';

    private $delete = 'delete from bookshelf where id=?';
    private $count = 'select count(*) from bookshelf';
    
    private $error = '';//ошибки валидации данных из формы
    
    public function __construct($pageNumber, $id)
    {
        $this->pageNumber = $pageNumber;
        $this->id = $id;
    }
    
    private function validate()
    {
        if (isset($_POST['fio'])) {
            $fio = trim($_POST['fio']);
            $this->data['fio'] = $fio;
            if (mb_strlen($fio) == 0) {
                $this->error .= 'Имя автора не может быть пустым<br>';
            }
        } else {
            $this->data['fio'] = '';
            $this->error .= 'Необходимо заполнить имя автора<br>';
        }
        
        if (isset($_POST['book_name'])) {
            $book_name = trim($_POST['book_name']);
            $this->data['book_name'] = $book_name;
            if (mb_strlen($book_name) == 0) {
                $this->error .= 'Название не может быть пустым<br>';
            }
        } else {
            $this->data['book_name'] = '';
            $this->error .= 'Необходимо заполнить название<br>';
        }
        if (isset($_POST['year'])) {
            $year = trim($_POST['year']);
            $this->data['year'] = $year;
            if (mb_strlen($year) == 0) {
                $this->error .= 'Год издания не может быть пустым<br>';
            }
        } else {
            $this->data['year'] = '';
            $this->error .= 'Необходимо заполнить год издания<br>';
        }
        if (isset($_POST['price'])) {
            $price = trim($_POST['price']);
            $this->data['price'] = $price;
            if (mb_strlen($price) == 0) {
                $this->error .= 'поле стоимость может быть пустым<br>';
            }
        } else {
            $this->data['price'] = '';
            $this->error .= 'Необходимо указать стоимость<br>';
        }
        
        return $this->error == '';//true если не было ошибки
    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {//проверка данных и запись в базу
            if ($this->validate()) {
                \Core\Db::exec($this->insert, [$this->data['fio'], $this->data['book_name'],$this->data['year'],$this->data['price'],]);
            }
        } else {//пустые данные для формы
            $this->data = ['id' => 0, 'book_name' => '', 'fio' => '','year' => '','price' => '',];
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



