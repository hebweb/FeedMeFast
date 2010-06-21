<?php
class StepFactory{
    public function __construct(PancakeTF_DBAccessI $db = null){
        $this->db = $db ? $db : new PancakeTF_PDOAccess;
    }
    
    public function getSteps($recipe_id){
        return new Steps($recipe_id);
    }
}