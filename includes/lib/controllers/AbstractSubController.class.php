<?php
abstract class AbstractSubController{
    
    abstract protected $actions;
    
    abstract protected $default_action;
    
    protected $model;
    
    protected $user;
    
    protected $env = 'xhtml';
    
    protected $action = '';
    
    protected $folder = '';
    
    public function __construct(Router $router, Savant3 $savant, $env = 'xhtml'){
        $this->router = $router;
        $this->view   = $savant;
        $this->user   = new User;
        $this->env    = $env;
        
        if (!in_array($router->getFolder(1),$this->actions)) $this->action = $this->actions[$this->default_action];
     }
     
     public public function execute(){
        $this->{$this->action};
     }
     
     protected function fetchTemplate($name){
         $file = dirname(__FILE__) . '/../templates/' . $this->folder . '/' . $this->enc . "/$name.tpl.php";
         if (file_exists($file)){
             return $this->view->fetch($this->folder . "/{$this->env}/$name.tpl.php");
         }else return $this->view->fetch($this->folder . "/xhtml/$name.tpl.php");
     }
     
     abstract public function getTitle();
     
     abstract public function getDescription();
     
     abstract public function generate();
}