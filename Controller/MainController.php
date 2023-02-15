<?php
namespace Controller;
class MainController 
{
    private $baseURL;
    private $page;
    private $action = "read";
    private $pageNumber = 1;
    private $id = 0;
    private $model;
    private $view;
    
    public function __construct($page, $baseURL)
    {
        $this->page = $page;
        $this->baseURL = $baseURL;
        $this->treatURL();
    }
    
    private function treatURL()
    {
        $url = $_SERVER['REQUEST_URI'];
        if (strpos($url, $this->baseURL) === 0) {
            $url = substr($url, strlen($this->baseURL));
        }
        if (strpos($url, 'index.php') === 0) {
            $url = substr($url, strlen('index.php'));
        }
        if (($pos = strpos($url, '?')) !== false) {
            $url = substr($url, 0, $pos);
        }
        $parameters = explode('/', $url);
        if (!empty($parameters[0])) $this->page = ucfirst(strtolower ($parameters[0]));
        if (!empty($parameters[1])) $this->action = strtolower ($parameters[1]);
        if (!empty($parameters[2])) $this->pageNumber = intval($parameters[2]);
        if (!empty($parameters[3])) $this->id = intval($parameters[3]);
    }
    
    public function run()
    {
        $modelName = 'Model\\' . $this->page;
        $this->model = new $modelName($this->pageNumber, $this->id);
        \Core\Db::init(DSN, LOGIN, PASSWORD);
        $this->model->{$this->action}();
        $viewName = 'View\\' . $this->page;
        $this->view = new $viewName($this->model);
        $this->view->{$this->action}();
    }
}



