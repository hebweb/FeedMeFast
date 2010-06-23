<?php
require_once dirname(__FILE__) . '/../../classes/MatkonTestCase.php';
require_once dirname(__FILE__) . '/../../../includes/lib/models/AbstractModel.class.php';
require_once dirname(__FILE__) . '/../../../includes/app/models/IngridiantsModel.class.php';

class IngridiantsModelGetTest extends MatkonTestCase{
    protected $sql = 'ingridiants';
    
    protected function setUpModel(array $options=array()){
        $this->setUpDB();
        $this->model = new IngridiantsModel($options,$this->db);
    }
    
    public function testSetUpDB(){
        $this->setUpDB();
        $this->assertEquals(3,$this->db->count('ingridiants',array()));
        $this->assertEquals(2,$this->db->count('ingridiant_manufactors',array()));
    }

    
    /**
     * @dataProvider provideIngridiant
     */
    public function testGetIngridiant($options,$id,$manufactors,$hot,$cold){
        $model = new IngridiantsModel($options,$this->db);
        $this->assertEquals(false,$model->getIngridiant());
        $model->execute();
        $ing = $model->getIngridiant();
        $this->assertEquals($id,$ing['id']);
        $this->assertEquals($manufactors,$ing['manufactors']);
        $this->assertEquals($hot,$ing['hot']);
        $this->assertEquals($cold,$ing['cold']);
    }
    
    static public function provideIngridiant(){
        return array(
            array(
                array('name'=>'milk')
                ,1
                ,array(
                    array('id'=>1,'name'=>'tnuva')
                    , array('id'=>2,'name'=>'tara')
                )
                ,true
                ,true
            )
            ,array(
                array('name'=>'sugar')
                ,2
                ,array()
                ,true
                ,true
            ),array(
                array('name'=>'salt')
                ,3
                ,array()
                ,true
                ,true
            )
        );
    }
    
    /**
     * @dataProvider provideGetIngridiantsByRecipeId
     */
    public function testGetIngridiantsByRecipeId($options,$ings){
        $options['action'] = IngridiantsAction::BY_RECIPE;
        $model = new IngridiantsModel($options,$this->db);
        $model->execute();
        $res = $model->getIngridiants();
        $this->assertEquals(count($ings),count($res));
        $this->assertEquals(count($ings[1]),count($res[1]));
        $this->assertEquals(count($ings[1]['manufactors']),count($res[1]['manufactors']));
        $this->assertEquals(count($ings[3]),count($res[3]));
        $this->assertEquals(count($ings[3]['manufactors']),count($res[3]['manufactors']));
    }
    
    static public function provideGetIngridiantsByRecipeId(){
        return array(
            array(
                array('recipe_id'=>1)
                ,array(
                    1=> array(
                        'id'=>1
                        ,'name'=>'milk'
                        ,'hot'=>1
                        ,'cold'=>1
                        ,'manufactors'=>array(
                            array('id'=>1,'name'=>'tnuva')
                            , array('id'=>2,'name'=>'tara')
                        )
                    )
                    ,3=>array(
                        'id'=>3
                        ,'name'=>'salt'
                        ,'hot'=>1
                        ,'cold'=>1
                        ,'manufactors'=>array()
                    )
                )
            )
        );
    }
    
    public function testSetErrorNoNameOnGetIng(){
        $this->setUpDB();
        $model = new IngridiantsModel();
        $this->assertFalse($model->isError(IngridiantsError::NO_NAME));
        $model->execute();
        $this->assertTrue($model->isError(IngridiantsError::NO_NAME));
    }
    
    public function testSetErrorInvalidNameOnGetIng(){
       $this->setUpModel(array('name'=>'a'));
       $this->assertFalse($this->model->isError(IngridiantsError::INVALID_NAME));
       $this->model->execute();
       $this->assertTrue($this->model->isError(IngridiantsError::INVALID_NAME));
    }
    
    public function testSetErrorNoRecipeId(){
        $options = array('action'=>IngridiantsAction::BY_RECIPE);
        $this->setUpModel($options);
        $this->assertFalse($this->model->isError(IngridiantsError::NO_RECIPE_ID));
        $this->model->execute();
        $this->assertTrue($this->model->isError(IngridiantsError::NO_RECIPE_ID));
    }
    
    public function testSetErrorInvalidRecipeId(){
        $options = array('action'=>IngridiantsAction::BY_RECIPE,'recipe_id'=>500);
        $this->setUpModel($options);
        $this->assertFalse($this->model->isError(IngridiantsError::INVALID_RECIPE_ID));
        $this->model->execute();
        $this->assertTrue($this->model->isError(IngridiantsError::INVALID_RECIPE_ID));
    }
    
    public function testList(){
        $options = array('action'=>IngridiantsAction::LIST_ALL);
        
        $this->setUpModel($options);
        
        $this->model->execute();
        
        $ings = $this->model->getIngridiants();
        
        $this->assertEquals(3,count($ings));
        
        $ids = array('1','2','3');
        $names = array('milk','sugar','salt');
        for ($i=0;$i<3;$i++){
            $this->assertEquals($ids[$i],$ings[$ids[$i]]['id']);
            $this->assertEquals($names[$i],$ings[$ids[$i]]['name']);
        }
    }
}