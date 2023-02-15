<?php
namespace View;

class Bookshelf {
    private $model;
    
    public function __construct($model)
    {
        $this->model = $model;
    }
    
    private function form($url)
    {
        echo '<div style="color:white; font-weight:bold; font-size:16px">';
        if ($this->model->getError()) {
            echo $this->model->getError();
        }
        echo '<form method="POST" action="' . $url . '">';
        $data = $this->model->getData();
        echo '<p> Автор <input type="text" name="fio" value="' . $data['fio'] . '"></p>';
        echo '<p>Название книги <textarea name="book_name">' . $data['book_name'] . '</textarea></p>';
        echo '<p> Год издания <input type="text" name="year" value="' . $data['year'] . '"></p>';
        echo '<p> Стоимость <input type="text" name="price" value="' . $data['price'] . '"></p>';
        echo '<input type="submit" value="Отправить">';
        echo '</form>';
        echo '</div>';
    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$this->model->getError()) {
            $url = "http://" . HOST . BASEURL . 
                     "bookshelf/read/1/";
            header("Location: $url");
        } else {
            $title = "Добавить книгу";
            include 'View/header.php';
            $this->form(BASEURL . 'bookshelf/create/' . $this->model->getPageNumber());
            include 'View/footer.php';
        }
    }
    
    public function read()
    {
        $pageNumber = $this->model->getPageNumber();
        $title = "каталог книг (Страница " . 
            $pageNumber . ")";
        include 'View/header.php';
        echo "<p><a href='" . BASEURL . 
                 "bookshelf/create/$pageNumber'>Добавить книгу</a></p>";
        $pageData = $this->model->getData();
        foreach ($pageData as $book) {
            echo '<div style="display:block; margin:0 20% 0 20%; color:white; font-weight:bold; font-size:16px">' . $book['fio'] . '<br>';
            echo nl2br($book['book_name']) . "  ";
            echo ($book['year']) .  '<br>';
            echo ($book['price']) . '</div>';
                     echo "<a href='" . BASEURL . 
                 "bookshelf/delete/$pageNumber/" .
                 $book['id']. "'>Удалить</a> ";
        }
        echo '<div style="color:white; font-weight:bold; font-size:16px"><p>';
        $pageCount = $this->model->getPagesCount();
        for ($i = 1; $i <= $pageCount; $i++) {
            if ($i == $pageNumber) {
                echo $i . ' ';
            } else {
                echo "<a href='" . BASEURL . 
                        "bookshelf/read/$i'>$i</a> ";
            }
        }
        echo '</p></div>';
        include 'View/footer.php';
    }
    
    
    public function delete()
    {
        $pageNumber = $this->model->getPageNumber();
        $url = "http://" . HOST . BASEURL . 
                 "bookshelf/read/$pageNumber/";
        header("Location: $url");
    }
    
}


