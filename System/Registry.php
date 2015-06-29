<?php
class System_Registry
{
    /**
     * Registry hash-table
     *
     * @var array
     */
    protected static $_registry = array();
 
    /**
     * Put item into the registry
     * 
     * @param string $key
     * @param mixed $item
     * @throws Exception
     * @return void
     */
    public static function set($key, $item)
    {
        if (!array_key_exists($key, self::$_registry)) {
            foreach (self::$_registry as $val) {
                if ($val === $item) {
                    throw new Exception('Item already exists');
                }
            }
            self::$_registry[$key] = $item;
        }
    }
    
    /**
     * Get item by key
     * 
     * @param string $key
     * @return false|mixed
     */
    public static function get($key)
    {
        if (array_key_exists($key, self::$_registry)) {
            return self::$_registry[$key];
        }
 
        return false;
    }
 
    /**
     * Remove item from the regisry
     * 
     * @param string $key
     * @return void
     */
    public static function remove($key)
    {
        if (array_key_exists($key, self::$_registry)) {
            unset(self::$_registry[$key]);
        }
    }
 
    protected function __construct()
    {
 
    }
}