<?php
class User{
    const DEFAULT_ID = 1;
    
    const SESSION_NAME = 'user';
    
    static private $info = array();
    
    static private $db = null;
    
    public function __construct(PancakeTF_DBAccessI $db=null){
        if ($db) self::$db = $db;
        elseif (!self::$db) self::$db = new PancakeTF_PDOAccess;
        
        if (isset($_SESSION[self::SESSION_NAME])){
            self::$info = $_SESSION[self::SESSION_NAME];
        }else $this->setId(self::DEFAULT_ID);
    }
    
    public function setId($id){
        $_SESSION[self::SESSION_NAME] = self::$info = self::$db->queryArray("SELECT * FROM `users` WHERE `id`=?",array($id));
        $this->getPermissions();
    }
    
    public function login($name){
        self::$info = self::$db->queryArray("SELECT * FROM `users` WHERE `name`=?",array($name));
        $this->getPermissions();
    }
    
    public function getPermissions(){}
    
    public function doesHavePermission($name){
        return ($name == 'admin' && self::$info['admin']==1);
    }
    
    public function isLoggedIn(){
        return self::$info['id'] != self::DEFAULT_ID;
    }
    
    public function __get($name){
        return (array_key_exists($name,self::$info)) ? self::$info[$name] : false;
    }
}