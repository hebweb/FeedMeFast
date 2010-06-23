<?php
require_once dirname(__FILE__) . '/../../classes/MatkonTestCase.php';
require_once dirname(__FILE__) . '/../../../includes/lib/models/AbstractModel.class.php';
require_once dirname(__FILE__) . '/../../../includes/app/models/IngridiantsModel.class.php';

class IngridiantsModelUpdateTest extends MatkonTestCase{
    protected $sql = 'ingridiants';
    
    protected function setUpModel(array $options=array()){
        $this->setUpDB();
        $this->model = new IngridiantsModel($options,$this->db);
    }
    
    /**
     * @dataProvider provideAddManufactor
     */
    public function testAddManufactor($id,$man_name){
        $options = array(
            'action'=>IngridiantsAction::ADD_MANUFACTORS
            , 'id' => $id
            , 'manufactor' => $man_name
        );
        
        $this->setUpModel($options);
        
        $this->model->execute();
        
        $this->assertEquals(1,$this->db->count('ingridiant_manufactors',array('name'=>$man_name,'ing_id'=>$id)));
    }
    
    static public function provideAddManufactor(){
        return array(
            array(1,'shupersal')
            , array(3,'tnuva')
            , array(2,'carmit')
        );
    }
    
    /**
     * @dataProvider provideBadIngridiantsId
     */
    public function testAddManufactorBadId($id){
        $options = array(
            'action'=>IngridiantsAction::ADD_MANUFACTORS
            , 'id' => $id
            , 'manufactor' => 'shupersal'
        );
        
        $this->setUpModel($options);
        
        $this->model->execute();
        
        $this->assertTrue($this->model->isError(IngridiantsError::BAD_ID));
    }
    
    static public function provideBadIngridiantsId(){
        return array(
            array(7),array(100),array(50), array('a')
        );
    }
    
    /**
     * @dataProvider provideExistingManufactors
     */
    public function testAddExistingManufactor($id,$man_name){
        $options = array(
            'action'=>IngridiantsAction::ADD_MANUFACTORS
            , 'id' => $id
            , 'manufactor' => $man_name
        );
        
        $this->setUpModel($options);
        
        $this->model->execute();
        
        
        $this->assertTrue($this->model->isError(IngridiantsError::MANUFACTOR_EXISTS));
    }
    
    static public function provideExistingManufactors(){
        return array(
            array(1,'tnuva')
            , array(1,'tara')
        );
    }
}