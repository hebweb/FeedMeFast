<?php
class IngridiantsController extends AbstractSubController{
    protected $actions = array(
        'list' => 'listIngridiants'
    );
    
    private $page_details = array(
        'list' => array('title'=>'list ingridiants','desc'=>'a list of all existing ingridiants')
    );
    
    protected $folder = 'ingridiants';
    
    protected $default_action = 'list';
    
    protected function listIngridiants(){
        $this->model = new IngridiantsModel();
        $this->model->setAction(IngridiantsAction::LIST_ALL);
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
        }
    }
}