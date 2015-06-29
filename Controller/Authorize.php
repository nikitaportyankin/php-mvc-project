<?php
class Controller_Authorize extends System_Controller
{    
    public function __construct()
    {
        if( $this->getParamByKey('save') == 'true' ) {
            if(!isset($_COOKIE[session_name()])) {    
                session_set_cookie_params(Model_User::LIFETIME_USER_COOKIE);
            }
        }
        parent::__construct();
    }
    
    public function indexAction()
    {
        
    }
    
    public function registerAction()
    {
        $params     = $this->getAllParams();

        $userModel  = new Model_User();
        try {
            $userId     = $userModel->register($params);
            $this->setSessParam('currentUser', $userId); 
            if($this->getParamByKey('save') == 'true') {
                $this->setSessParam('is_save', 1);   
            }
            $userData = array(
                'id'    =>  $userId,
                'email' =>  trim($params['email'])
            );
           
            echo json_encode($userData); 
            die();
        }
        catch(Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
            die();
        }
    }
    
    public function loginAction()
    {
        $params     = $this->getAllParams();
        $userModel  = new Model_User();
        try {
            $user = $userModel->login($params);
            $this->setSessParam('currentUser', $user->id);
            if($this->getParamByKey('save') == 'true') {
                $this->setSessParam('is_save', 1);   
            }
            $userData = array(
                'id'        =>  $user->id,
                'email'     =>  trim($params['email']),
                'role_id'   =>  $user->role_id
            );
            
            echo json_encode($userData);
            die();
        }
        catch(Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
            die();
        }
    }
    
    public function exitAction()
    {
        $_SESSION = array();
        unset($_COOKIE[session_name()]);
        session_destroy();
        setcookie(session_name(), '', time(), '/');
        die();
    }        
}