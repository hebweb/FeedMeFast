<?php
class RecipeModel extends AbstractModel{
    
    /**
     * @param array holds all legal actions for the model and their paired methods
     * @access protected
     */
    protected $_actions = array(
        'create' => 'createRecipe'
        , 'read' => 'fetchRecipe'
        , 'update' => 'updateRecipe'
        , 'delete' => 'deleteRecipe'
    );
    
    /**
     * @param string holds default action for the model
     * @access protected
     */
    protected $_default_action = 'read';
    
    /**
     * @param string current model action
     * @access protected
     */
    protected $_action = false;
    
    public function __construct($options = array(), SetpFactory $steps_factory = null , IngridiantFactory $ingridiants_factory = null, PancakeTF_DBAccessI $db=null){
        $db = $db ? $db : new PancakeTF_PDOAccess;
        $this->steps_factory = $steps_factory ? $steps_factory : new StepFactory($this->db);
        $this->ingridiants_factory  = $ingridiants_factory ? $ingridiants_factory : new IngridiantFactory($this->db);
        parent::__construct($options,$db);
    }
}