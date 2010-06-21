<?php
require_once dirname(__FILE__) . "/../../../includes/lib/DBAccess/PancakeTF_PDOAccess.class.php";

$_SESSION = array();

abstract class UserAtuhTestCase extends PHPUnit_Framework_Testcase{
    
    protected function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = false, $callOriginalClone = TRUE, $callAutoload = TRUE){
        return parent::getMock($originalClassName,$methods,$arguments,$mockClassName,$callOriginalConstructor,$callOriginalClone,$callAutoload);
    }
    
    protected $db = null;
    
    public function setUpDB(){
        PancakeTF_PDOAccess::connect('mysql','localhost','user_atuh','root','1234');
        $this->db = new PancakeTF_PDOAccess();
        $sql = file_get_contents(dirname(__FILE__).'/../sql/user_atuh.sql');
        $sql = explode (';',$sql);
        foreach ($sql as $stmt){
            try{
                $this->db->update($stmt);
            }catch (Exception $e){}
        }
    }
}