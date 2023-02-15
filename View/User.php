<?php
namespace View;

class User {
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
        echo '<p>Логин <input type="text" name="login" value="' . $data['login'] . '"></p>';
        echo '<p>Пароль <input type="password" name="password" value="' . $data['password'] . '"></p>';
        echo '<p>Выберите тип литературы какой вы предпочитаете <br><input type="radio" name="type_lit" value="роман" ' . 
                ($data['type_lit'] == 'роман'?'checked':'') . '>роман<br>';
        echo '<input type="radio" name="type_lit" value="детектив" ' .
                ($data['type_lit'] == 'детектив'?'checked':'') . '>детектив</p>';
        echo '<input type="radio" name="type_lit" value="фантастика" ' .
                ($data['type_lit'] == 'фантастика'?'checked':'') . '>фантастика</p>';
        echo     '<input type="radio" name="type_lit" value="научная литература" ' .
                ($data['type_lit'] == 'научная литература'?'checked':'') . '>научная литература</p>';
        echo '<input type="submit" value="Отправить">';
        echo '</form>';
        echo '</div>';
    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$this->model->getError()) {
            $url = "http://" . HOST . BASEURL . 
                     "user/read/1/";
            header("Location: $url");
        } else {
            $title = "Добавить пользователя";
            include 'View/header.php';
            $this->form(BASEURL . 'user/create/' . $this->model->getPageNumber());
            include 'View/footer.php';
        }
    }
    
    public function read()
    {
        $pageNumber = $this->model->getPageNumber();
        $title = "Пользователи (Страница " . 
            $pageNumber . ")";
        include 'View/header.php';
        echo "<p><a href='" . BASEURL . 
                 "user/create/$pageNumber'>Добавить пользователя</a></p>";
        $pageData = $this->model->getData();
        foreach ($pageData as $message) {
            echo '<div style="color:white; font-weight:bold; font-size:16px">Логин: ' . $message['login'] . '<br>';
            echo 'Tип литературы: ' . $message['type_lit'] . '<br>';
            echo "<a href='" . BASEURL . 
                 "user/delete/$pageNumber/" .
                 $message['id']. "'>Удалить</a> ";
            echo "<a href='" . BASEURL . 
                 "user/update/$pageNumber/" .
                 $message['id']. "'>Редактировать</a><hr>";
        }
        echo '<div style="color:white; font-weight:bold; font-size:16px"><p>';
        $pageCount = $this->model->getPagesCount();
        for ($i = 1; $i <= $pageCount; $i++) {
            if ($i == $pageNumber) {
                echo $i . ' ';
            } else {
                echo "<a href='" . BASEURL . 
                        "user/read/$i'>$i</a> ";
            }
        }
        echo '</p></div>';
        include 'View/footer.php';
    }
    
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$this->model->getError()) {
            $url = "http://" . HOST . BASEURL . 
                     "user/read/" . $this->model->getPageNumber();
            header("Location: $url");
        } else {
            $title = "Редактировать сообщение";
            include 'View/header.php';
            $this->form(BASEURL . 'user/update/' . $this->model->getPageNumber() . "/" .
                     $this->model->getId());
            include 'View/footer.php';
        }
    }
    
    public function delete()
    {
        $pageNumber = $this->model->getPageNumber();
        $url = "http://" . HOST . BASEURL . 
                 "user/read/$pageNumber/";
        header("Location: $url");
    }
}


