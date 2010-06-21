<?php
class HTMLController{
    private $css = array();
    
    private $js = array();
    
    private $titles = array('my-site');
    
    private $desc = 'some-desc';
    
    private $output = '';
    
    private $sub_controller = false;
    
    public function __construct(Router $router, Savant3 $view, PancakeTF_DBAccessI $db=null, $online=true){
        $this->router = $router;
        $this->view = $view;
        $this->db = ($db) ?  $db  : new PancakeTF_PDOAccess;
        
        $this->getSubController();
        
        $this->setCSS();
        
        $this->setJS();
        //$user = new User;
        
        /*if (!$user->isLoggedIn()){
            $handler = new KeyHandler(new MatkonDba);
            $this->view->assign('key_handler',$handler);
        }*/
        
        $this->view->assign('css',$this->css);
        $this->view->assign('js',$this->js);
        $this->view->assign('titles',$this->titles);
        $this->view->assign('description',$this->desc);
        $this->view->assign('sub_controller',$this->sub_controller);
        $this->view->assign('online',(bool)$online);
        //$this->view->assign('user',$user);
        $this->view->assign('menu',new Menu);
    }
    
    public function getSubController(){
        
    }
    
    public function setCSS(){}
    
    public function setJS(){}
    
    public function generate(){
       $this->output .= $this->view->fetch('/html/header.tpl.php');
       $this->output .= $this->view->fetch('/html/body.tpl.php');
       $this->output .= $this->view->fetch('/html/footer.tpl.php');
       
       return $this->output;
    }
}