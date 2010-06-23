<?php
class IngridiantsController extends AbstractSubController{
    protected $actions = array(
        'list' => 'listIngridiants'
        , 'add' => 'add'
        , 'post' => 'post'
    );
    
    private $page_details = array(
        'list' => array('title'=>'list ingridiants','desc'=>'a list of all existing ingridiants')
        ,'add' => array('title'=>'add ingridiant/s','desc'=>'a form for adding ingridiant/s')
        ,'post' =>array('title'=>'post ingridiant/s','desc'=>'posting new ingridiant/s')
    );
    
    protected $folder = 'ingridiants';
    
    protected $default_action = 'list';
    
    protected function listIngridiants(){
        $this->model = new IngridiantsModel();
        $this->model->setAction(IngridiantsAction::LIST_ALL);
        $this->model->execute();
        $this->view->assign('model',$this->model);
    }
    
    protected function add(){
        $this->js[]='add-ingridiants';
    }
    
    protected function post(){
        $name = $this->router->name;
        $hot = $this->router->isParamSet('hot') ? ($this->router->hot==1) : true;
        $cold = $this->router->isParamSet('cold') ? ($this->router->cold==1) : true;
        $manus = $this->router->isParamSet('manufactors') ? $this->router->manufactors : array();
        
        $this->model = new IngridiantsModel(array(
            'name'=>$name
            ,'hot'=>$hot
            ,'cold'=>$cold
            ,'manufactors'=>$manus
        ));
        $this->model->setAction(IngridiantsAction::CREATE_SINGLE);
        $this->model->execute();
        
        $this->view->assign('model',$this->model);
    }
    
    public function getTitle(){
        return $this->page_details[$this->action]['title'];
    }
    
    public function getDescription(){
        return $this->page_details[$this->action]['desc'];
    }
    
    public function generate(){
        switch ($this->action){
            case 'list':
                $this->folder .='/list';
                return $this->fetchTemplate('default');
            break;
            case 'add':
                $this->folder .='/add';
                if ($this->router->getFolder(2)=='multiple') return $this->fetchTemplate('multiple');
                return $this->fetchTemplate('default');
            break;
            case 'post':
                $this->folder .= '/post';
                if ($this->router->getFolder(2)=='multiple') return $this->fetchTemplate('multiple');
                return $this->fetchTemplate('default');
        }
    }
}
