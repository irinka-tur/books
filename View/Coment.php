<?php
namespace View;

class Coment {
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
        echo '<p> Автор <input type="text" name="name" value="' . $data['name'] . '"></p>';
        echo '<p>Комментарий <textarea name="com">' . $data['com'] . '</textarea></p>';
        echo '<input type="submit" value="Отправить">';
        echo '</form>';
        echo '</div>';
    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$this->model->getError()) {
            $url = "http://" . HOST . BASEURL . 
                     "coment/read/1/";
            header("Location: $url");
        } else {
            $title = "Добавить комментарий";
            include 'View/header.php';
            $this->form(BASEURL . 'coment/create/' . $this->model->getPageNumber());
            include 'View/footer.php';
        }
    }
    
    public function read()
    {
        $pageNumber = $this->model->getPageNumber();
        $title = "Коментарии (Страница " . 
            $pageNumber . ")";
        include 'View/header.php';
        echo "<p><a href='" . BASEURL . 
                 "coment/create/$pageNumber'>Добавить комментарий</a></p>";
        $pageData = $this->model->getData();
        foreach ($pageData as $coment) {
            echo '<div style="display:block; margin:0 20% 0 20%; color:white; font-weight:bold; font-size:16px">' . $coment['name'] . '<br>';
            echo nl2br($coment['com']) .  '</div>';
                     echo "<a href='" . BASEURL . 
                 "coment/delete/$pageNumber/" .
                 $coment['id']. "'>Удалить</a> ";
        }
        echo '<div style="color:white; font-weight:bold; font-size:16px"><p>';
        $pageCount = $this->model->getPagesCount();
        for ($i = 1; $i <= $pageCount; $i++) {
            if ($i == $pageNumber) {
                echo $i . ' ';
            } else {
                echo "<a href='" . BASEURL . 
                        "coment/read/$i'>$i</a> ";
            }
        }
        echo '</p></div>';
        include 'View/footer.php';
    }
    
    
    public function delete()
    {
        $pageNumber = $this->model->getPageNumber();
        $url = "http://" . HOST . BASEURL . 
                 "coment/read/$pageNumber/";
        header("Location: $url");
    }
    
}




