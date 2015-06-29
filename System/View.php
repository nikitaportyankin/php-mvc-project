<?php
class System_View
{
    private $_params = array();
    
    public function setParam($key, $value)
    {
        $key = (string)$key;
        $this->_params[$key] = $value;
    }
    
    public function getParam($key)
    {
        $key = (string)$key;
        return (!empty($this->_params[$key])) ? $this->_params[$key] : '';
    }
}