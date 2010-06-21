<?php
require_once '/Model.class.php';
require_once '/MatkonTestCase.php';

abstract class ModelTester extends MatkonTestCase{
    public function setUpModel($options=array(),$db=false){
        $this->db = $db ? $db : $this->getMock('PancakeTF_DBAccessI');
        $this->model = new Model($options,$this->db);
    }
}