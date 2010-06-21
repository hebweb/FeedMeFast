<?php
require_once dirname(__FILE__) . '/../../classes/MatkonTestCase.php';
require_once dirname(__FILE__) . '/../../../includes/lib/models/AbstractModel.class.php';
require_once dirname(__FILE__) . '/../../../includes/app/models/IngridiantsModel.class.php';

class IngridiantsModelCreateTest extends MatkonTestCase{
    protected $sql = 'ingridiants';
    
    protected function setUpModel(array $options=array()){
        $this->setUpDB();
        $this->model = new IngridiantsModel($options,$this->db);
    }
    
    public function testCreateIngridiant(){
        $options = array(
            'action'=>IngridiantsAction::CREATE_SINGLE
            , 'name' => 'choclate'
            , 'hot' => false
            , 'cold' => true
            , 'manufactors' => array('elit','vered-hagalil')
        );
        
        $this->setUpModel($options);
        $this->model->execute();
        
        $count = $this->db->count('ingridiants',array('name'=>'choclate'));
        $this->assertEquals(1,$count);
        /*
         * check getIngriditant
         */
        $exp = array(
            'id'=>4
            ,'name'=>'choclate'
            ,'manufactors'=>array(
               'elit','vered-hagalil'
            )
            ,'hot'=>0
            ,'cold'=>1
        );
        
        $res = $this->model->getIngridiant();
        
        $this->assertEquals($exp,$res);
        
        /*
         * check db
         */
        $options = array('name'=>'choclate');
        $model = new IngridiantsModel($options);
        $model->execute();
        $this->assertFalse($model->isError());
        $exp = array(
            'id'=>4
            ,'name'=>'choclate'
            ,'manufactors'=>array(
                array('id'=>3,'name'=>'elit')
                , array('id'=>4,'name'=>'vered-hagalil')
            )
            ,'hot'=>false
            ,'cold'=>true
        );
        
        $res = $model->getIngridiant();
        $this->assertEquals($exp,$res);
    }

    public function testCreateIngridiants(){
        $options = array(
            'action'=>IngridiantsAction::CREATE_MULTIPLE
            , 'ingridiants'=>array(
                array(
                    'name' => 'choclate'
                    , 'hot' => false
                    , 'cold' => true
                    , 'manufactors' => array('elit','vered-hagalil')
                )
                ,array(
                    'name' => 'flower'
                    , 'hot' => true
                    , 'cold' => false
                    , 'manufactors' => array('osem')
                )
                ,array(
                    'name'=> 'rice'
                    ,'cold'=> false
                )
            )
        );
        
        $this->setUpModel($options);
        $this->model->execute();
        /*
         * test getIngridiants
         */
        $exp = array(
                array(
                    'id'=>4
                    , 'name' => 'choclate'
                    , 'hot' => 0
                    , 'cold' => 1
                    , 'manufactors' => array('elit','vered-hagalil')
                )
                ,array(
                    'id'=>5
                    , 'name' => 'flower'
                    , 'hot' => 1
                    , 'cold' => 0
                    , 'manufactors' => array('osem')
                )
                ,array(
                    'id'=>6
                    , 'name'=> 'rice'
                    , 'hot' => 1
                    , 'cold' => 0
                    , 'manufactors'=>array()
                )
        );
        
        $this->assertEquals($exp,$this->model->getIngridiants());
        /*
         * test db
         */
        $exp = array(
                array(
                    'id'=>4
                    , 'name' => 'choclate'
                    , 'hot' => false
                    , 'cold' => true
                    , 'manufactors' => array(array('id'=>3,'name'=>'elit'),array('id'=>4,'name'=>'vered-hagalil'))
                )
                ,array(
                    'id'=>5
                    , 'name' => 'flower'
                    , 'hot' => true
                    , 'cold' => false
                    , 'manufactors' => array(array('id'=>5,'name'=>'osem'))
                )
                ,array(
                    'id'=>6
                    , 'name'=> 'rice'
                    , 'hot' => true
                    , 'cold' => false
                    , 'manufactors'=>array()
                )
        );
        
        $names = array('choclate','flower','rice');
        $model = new IngridiantsModel();
        for ($i=0;$i<3;$i++){
            $model->setOption('name',$names[$i]);
            $model->execute();
            $this->assertEquals($exp[$i],$model->getIngridiant());
        }
    }
}