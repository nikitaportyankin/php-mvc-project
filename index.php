<?php
define('DS', DIRECTORY_SEPARATOR);
// Узнаём путь к файлам сайта
$site_path = realpath(dirname(__FILE__) . DS) . DS;
define('SITE_PATH', $site_path);

$config     = file_get_contents(SITE_PATH . DS . 'config.xml');

$configXML  = new SimpleXMLElement($config);

$host       = (string)$configXML->db->host;
$dbname     = (string)$configXML->db->dbname;  
$username   = (string)$configXML->db->username;
$password   = (string)$configXML->db->password;

try {
    $db = new PDO('mysql:host='.$host.';dbname='.$dbname, $username, $password);
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage();
}

spl_autoload_register('loadClass');

function loadClass($className)
{
    $path       =   explode('_', $className);
    
    $file = '';
    foreach ($path as $item) {
        $file .= $item . DS;
    }
    
    $file = substr($file, 0, -1) . '.php';

    if (!file_exists($file)) {
        return false;
    }
     
    include $file;
}

try {
    System_Registry :: set('db', $db);
}
catch(Exception $e) {
    echo $e->getMessage();
}

$router = new System_Router();

try {
    $router->setPath(SITE_PATH . 'Controller');
    $router->start();
}
catch(Exception $e) {
    echo $e->getMessage();
}