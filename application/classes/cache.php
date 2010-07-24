<?php defined('SYSPATH') or die('No direct script access.');

abstract class Cache extends Kohana_Cache 
{
    public static function key($method = '', $id = '')
    {
        if (is_array($method))
        {
            $method = implode('__', $method);
        }
        
        if (is_array($id))
        {
            $id = implode('__', $id);
        }
        
        return get_called_class() . '__' . $method . '__' . $id;
    }
}