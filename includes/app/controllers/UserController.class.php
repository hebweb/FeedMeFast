<?php
class UserController extends AbstractSubController{
    protected $actions = array(
        'login'=>'login'
        ,'logout'=>'logout'
        ,'create'=>'create'
        ,'user-page'=>'userPage'
    );
    
    protected $folder = 'user';
    
    protected $default_action = 'user-page';

    protected function login(){
        $handler = new keyHandler(new userAtuhDba());
        $user = $this->router->{'name'};
        $key  = $this->router->enc;
        $valid = $handler->authenticate($name,$key);
        $user = new User;
        
        if ($valid){
            $user->login($name);
        }else $user->setId(User::DEFAULT_ID);
        
        $this->view->assign('logged_in',$valid);
    }
    
    protected function logout(){
        $user->setId(User::DEFAULT_ID);
    }
    
    protected function create(){
        $options = array(
            'action'=>'create'
            ,'name'=>$this->router->getParam('name')
            ,'email'=>$this->router->getParam('email')
            ,'pass'=>$this->router->getParam('pass')
        );
        
        $model = new UserModel($options);
        $model->execute();
        $this->view->assign('model',$model);
        if (!$this->model->isError()){
           $user = new User;
           $user->setId($model->getId());
        }
    }
    
    public function getTitle(){}
    
    public function getDescription(){}
    
    public function generate(){
        switch ($this->action){
            case 'login':
                $page = 'login';
            break;
        }
        
        return $this->fetchTemplate($page);
    }
}