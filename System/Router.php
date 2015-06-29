<?php
class System_Router
{
    private $path;

    public function setPath($path)
    {
        $path = trim($path, '/\\');
        $path .= DS;
            
        if (is_dir($path) == false) {
            throw new Exception ('Invalid controller path: `' . $path . '`');
        }
        
        $this->path = $path;
    }
    
    public function start()
    {
        // Анализируем путь
        $this->_getController($file, $controllerName, $action, $args);

        // Файл доступен?
        if (is_readable($file) == false) {
            throw new Exception('404 error! Controller ' . '\'' . $controllerName . '\''. ' not found');
        }
       
        // Подключаем файл
        include ($file);
   
        // Создаём экземпляр контроллера
        $class = 'Controller_' . ucfirst($controllerName);
       
        $viewFolder = ucfirst($controllerName);
      
        $controller = new $class();
        
        // Действие доступно?
        if (is_callable(array($controller, $action)) == false) {
            throw new Exception('404 error. Action ' . '\'' . $action . '\''. ' Not Found');
        }
        
        /**
         * @var System_View $view
         */
        $view = $controller->view;
        
        $controller->args = $args;    
       
        // Выполняем действие
        //$controller->$action();
     
        call_user_func(array($controller, $action));
        
        $actionName = substr($action, 0, -6);
        
        $layoutFileName = 'View' . DS . 'layout.phtml';
        $viewFileName = 'View' . DS . $viewFolder . DS . $actionName . '.phtml';
        
        include $layoutFileName;
    }
    
    private function _getController(&$file, &$controller, &$action, &$args)
    {
        $route = (empty($_GET['route'])) ? 'index' : $_GET['route'];
        
        // Получаем раздельные части
        $route = trim($route, '/\\');
        $parts = explode('/', $route);
        
         // Находим правильный контроллер
        $cmd_path = $this->path;
        foreach ($parts as $part) {

            $part = ucfirst($part);
       
            if(!$controller) {
                $cmd_path .= $part . DS;
                $controller = array_shift($parts);
                continue;
            }
          
            // Находим файл
            if(!$action) {
                $action = array_shift($parts);
                break;
            }
        }

        if(empty($controller)) {
            $controller = 'Index';
        }
        
        if (empty($action)) { 
            $action = 'indexAction';
        }
        else {
            $action .= 'Action';
        }
       
        $file = trim($cmd_path, '/\\') . '.php';
       
        $args = $parts;
    }
}