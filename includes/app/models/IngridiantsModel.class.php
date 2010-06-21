<?php
class IngridiantsError{
   const NO_NAME = 1;
   const INVALID_NAME = 2;
   const NO_RECIPE_ID = 3;
   const INVALID_RECIPE_ID = 4;
   const NAME_EXISTS = 5;
   const NO_INGRIDIANTS = 6;
}

class IngridiantsAction{
    const BY_NAME = 1;
    const BY_ID = 2;
    const BY_RECIPE = 3;
    const CREATE_SINGLE = 4;
    const CREATE_MULTIPLE = 5;
}

class IngridiantsModel extends AbstractModel{
    /**
     * @param array holds all legal actions for the model and their paired methods
     * @access protected
     */
    protected $_actions = array(
        IngridiantsAction::BY_NAME => 'retriveByName'
        , IngridiantsAction::BY_RECIPE => 'retriveByRecipe'
        , IngridiantsAction::CREATE_SINGLE => 'createIngridiant'
        , IngridiantsAction::CREATE_MULTIPLE => 'createIngridiants'
    );
    
    /**
     * @param string holds default action for the model
     * @access protected
     */
    protected $_default_action = IngridiantsAction::BY_NAME;
    
    /**
     * @param string current model action
     * @access protected
     */
    protected $_action = false;
    
    protected $_ingridiants = array();
    
    protected function retriveByName(){
        $name = $this->getOption('name');
        
        if (!$name) $this->setError(IngridiantsError::NO_NAME);
        if (!$this->doesIngridiantExists($name)) $this->setError(IngridiantsError::INVALID_NAME);
        
        if ($this->isError()) return;
        
        $sql = "SELECT
                ingridiants.id,
                ingridiants.name,
                ingridiant_details.*,
                ingridiant_manufactors.name as `manu_name`,
                ingridiant_manufactors.id as `manu_id`
                FROM
                ingridiants
                left Join ingridiant_manufactors ON ingridiant_manufactors.ing_id = ingridiants.id
                Inner Join ingridiant_details ON ingridiant_details.id = ingridiants.id
                WHERE `ingridiants`.`name`=?";
        $res = $this->db->queryArray($sql,array($name));
        
        $ing = $this->orgenizeIngridiantData($res[0]);
        $ing['manufactors'] = array();
        if ($res[0]['manu_id']) foreach ($res as $row) $ing['manufactors'][] = $this->orgenizeManufactorData($row);
        
        $this->_ingridiants[]=$ing;
    }
    
    protected function retriveByRecipe(){
        $rec_id = $this->getOption('recipe_id');
        
        if (!$rec_id) $this->setError(IngridiantsError::NO_RECIPE_ID);
        if (!$this->doesRecipeExists($rec_id)) $this->setError(IngridiantsError::INVALID_RECIPE_ID);
        
        if ($this->isError()) return;
        
        $sql = "SELECT
                    ingridiants.id,
                    ingridiants.name,
                    ingridiant_details.*,
                    ingridiant_manufactors.name as `manu_name`,
                    ingridiant_manufactors.id as `manu_id`
                FROM
                ingridiants
                Left Join ingridiant_manufactors ON ingridiant_manufactors.ing_id = ingridiants.id
                Inner Join ingridiant_details ON ingridiant_details.id = ingridiants.id
                Inner Join ingridiants_has_recipe ON ingridiants.id = ingridiants_has_recipe.ingridiants_id
                WHERE  ingridiants_has_recipe.recipe_id=?";
        
        $ingridiants = array();
        $last_id = false;
        foreach($this->db->queryArray($sql,array($rec_id)) as $raw){
            if ($last_id!=$raw['id']){
                $last_id = $raw['id'];
                $ingridiants[$last_id]=$this->orgenizeIngridiantData($raw);
                $ingridiants[$last_id]['manufactors'] = array();
            }
            
            if ($raw['manu_id']) $ingridiants[$last_id]['manufactors'][]=$this->orgenizeManufactorData($raw);
        }
        
        $this->_ingridiants = $ingridiants;
    }
    
    protected function createIngridiant(){
        $name = $this->getOption('name');
        if ($this->isOptionSet('hot'))
            $hot = $this->getOption('hot') ? 1: 0;
        else $hot = 1;
        
        if ($this->isOptionSet('cold'))
            $cold = $this->getOption('cold') ? 1 : 0;
        else $cold = 1;
        
        $manufactors = $this->isOptionSet('manufactors') ? $this->getOption('manufactors') : false;
        if (!$name) $this->setError(IngridiantsError::NO_NAME);
        if ($this->doesIngridiantExists($name)) $this->setError(IngridiantsError::NAME_EXISTS);
        
        if ($this->isError()) return;
        
        $this->create(array(array('name'=>$name,'hot'=>$hot,'cold'=>$cold,'manufactors'=>$manufactors)));
    }
                       
    protected function createIngridiants(){
        $ings = $this->getOption('ingridiants');

        if (!$ings) $this->setError(IngridiantsError::NO_INGRIDIANTS);
        foreach ($ings as $ing){
            if (!array_key_exists('name',$ing)) $this->setError(IngridiantsError::NO_NAME);
            if ($this->doesIngridiantExists($ing['name'])) $this->setError(IngridiantsError::NAME_EXISTS);
        }
        
        if ($this->isError()) return;
        
        $this->create($ings);
    }
    
    private function create(array $ings){
        foreach ($ings as $ing){
            $hot = (array_key_exists('hot',$ing)) ?
                $ing['hot'] ? 1 : 0
                : 1;
            $cold = (array_key_exists('cold',$ing)) ?
                $ing['cold'] ? 1 : 0
                : 1;
            $manufactors =  (array_key_exists('manufactors',$ing) && is_array($ing['manufactors'])) ?
                $ing['manufactors']
                : array();
            $this->db->update( "INSERT INTO `ingridiants` (`name`) VALUES (?)" , array($ing['name']) );
            
            $id = $this->db->getLastId();
            
            $this->db->update( "INSERT INTO `ingridiant_details` (`id`,`hot`,`cold`) VALUES (?,?,?)" , array($id,$hot,$cold) );
            
            if ($manufactors) $this->insertManufactors($id,$manufactors);
            
            $this->_ingridiants[] = array(
                'id'=>$id
                ,'name'=>$ing['name']
                ,'hot'=>$hot
                ,'cold'=>$cold
                ,'manufactors'=>$manufactors
            );
        }
    }
    
    private function orgenizeIngridiantData($data){
        $ing['id'] = $data['id'];
        $ing['name'] = $data['name'];
        $ing['hot'] =  array_key_exists('hot',$data) ? (bool)$data['hot'] : true;
        $ing['cold'] =  array_key_exists('cold',$data) ? (bool)$data['cold'] : true;
        
        return $ing;
    }
    
    private function orgenizeManufactorData($data){
        $manu = ($data['manu_id']) ?
           $manu=array('id'=>$data['manu_id'],'name'=>$data['manu_name'])
           : array();
        
        return $manu;
    }
    
    private function doesIngridiantExists($name){
        return (bool)$this->db->count('ingridiants',array('name'=>$name));
    }
    
    private function doesRecipeExists($rec_id){
        return (bool)$this->db->count('recipes',array('id'=>$rec_id));
    }
    
    private function insertManufactors($id,$manufactors){
        foreach ($manufactors as $man){
            $this->db->update("INSERT INTO `ingridiant_manufactors`(`ing_id`,`name`) VALUES (?,?)",array($id,$man));
        }
    }
}