<?php
class Model_User
{
    public  $id;
    public  $login;
    public  $email;
    private $_password;
    public  $photo;
    public  $role_id;
    
    const ROLE_ADMIN_ID = 1;
    const MODE_REGISTER = 1;
    const MODE_LOGIN    = 2;
    
    const LIFETIME_USER_COOKIE = 10800;//3 hours
    
    public function __construct()
    {

    }
    
    public function create($login, $password)
    {
        $dbUser = new Model_Db_Table_User();
        $dbUser->create($login,$password);
    }
    
    /**
     * 
     * @param int $userId
     * @return Model_User
     * @throws Exception
     */
    public static function getById($userId)
    {
        $dbUser     =  new Model_Db_Table_User();

        $userData   =  array_shift($dbUser->getById($userId));
        //$userData   =  reset($dbUser->getById($userId));
        
        if($userData) {
            $modelUser  = new self();
            $modelUser->id          = $userData->id;
            $modelUser->login       = $userData->login;
            $modelUser->email       = $userData->email;
            $modelUser->photo       = $userData->photo;
            $modelUser->role_id     = $userData->role_id;

            return $modelUser;
        }
        else {
            throw new Exception('User not found', System_Exception::NOT_FOUND);
        }
    }
    
    /**
     * 
     */
    public function getFullName()
    {
        return $this->login . ' ' . $this->email;
    }        
    
    
    public function save()
    {
        $tableUser = new Model_Db_Table_User();
        $tableUser->save($this);
    }  
    
    public function setEmail($value)
    {
        $this->email = $value;
    }
    
    /**
     * 
     * @param array $params
     * @throws Exception
     */
    public function register($params)
    {
        if(!$this->_validate($params))
        {
            throw new Exception('The entered data is invalid', System_Exception::VALIDATE_ERROR);
        }
        
        $tableUser = new Model_Db_Table_User();
   
        $resIfExists = $tableUser->checkIfExists($params);
        
        if(!empty($resIfExists)) {
            throw new Exception('Such account is already exists.', System_Exception :: ALREADY_EXIST);
        }
        else {
            $resCreate = $tableUser->create($params);

            if(!$resCreate) {
                throw new Exception('Can\'t create new user. Try later.', System_Exception :: ERROR_CREATE_USER);
            }
            return $resCreate;
        }
    }
    
    
    /**
     * 
     * @param array $params
     * @return int userId
     * @throws Exception
     */
    public function login($params)
    {
        if(!$this->_validate($params))
        {
            throw new Exception('The entered data is invalid', System_Exception::VALIDATE_ERROR);
        }
        $tableUser = new Model_Db_Table_User();
        
        $res = $tableUser->checkIfExists($params, Model_User::MODE_LOGIN);
        
        if(!empty($res)) {
            $user = reset($res);
            return $user; 
        }
        else {
            throw new Exception('Invalid user or password.', System_Exception::INVALID_LOGIN);
        }
    }
    
    /**
     * 
     * @param array $params
     * @return boolean
     */
    private function _validate($params)
    {
        $login      = !empty($params['email']) ? $params['email'] : '';
        $password   = !empty($params['password']) ? $params['password'] : '';
        
        
        if(empty($password)) {
            return false;
        }
        
        if(strlen($login > 20)) {
            return false;
        }
        if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }   
}