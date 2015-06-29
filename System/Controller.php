<?php
abstract class System_Controller
{
    /**
     *
     * @var System_View $view 
     */
    public $view;

    public $args;
    
    protected $_userId;
    
    protected function _getArguments()
    {
        $count = count($this->args);
        $arguments = array();
        
        for($i = 0; $i < $count - 1; $i += 2) {
            $arguments[$this->args[$i]] = $this->args[$i + 1];
        }
        
        return $arguments;
    }

    public function __construct()
    {
        session_start();
        if( isset($_COOKIE[session_name()]) && $this->getParamByKey('save') == 'true') {
            setcookie(session_name(), session_id(), time() + Model_User::LIFETIME_USER_COOKIE, '/');
        }
        $this->view = new System_View();
        $this->_userId = $this->getSessParam('currentUser');
    }

    abstract function indexAction();
    
    public function getAllParams()
    {
        return $_REQUEST;
    }
    
    public function getParamByKey($key)
    {
        return !empty($_REQUEST[$key]) ? $_REQUEST[$key] : NULL;
    }


    protected function setSessParam($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    protected function getSessParam($key)
    {   
        if(!empty($_SESSION)) {
            return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : NULL;
        }
        return NULL;
    }
    
    public function getUserId()
    {
        return $this->_userId;
    }
}