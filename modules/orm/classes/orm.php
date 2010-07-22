<?php defined('SYSPATH') or die('No direct script access.');

class ORM extends Kohana_ORM {
    
    /**
     * Is there actually a record with this ID in the model?
     * @param integer $id primary key
     */
    public static function exists($id)
    {
        $class = get_called_class();
        $inst  = new $class();
        
        // we can access $inst's protected member, since we $inst is guaranteed 
        // to have inherited from ORM
        return $inst->where($inst->_primary_key, '=', $id)->count_all() == 1;
    }
}