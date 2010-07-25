<?php defined('SYSPATH') or die('No direct script access.');

class Model_Type extends ORM {
    protected $_has_one = array('replay' => array());
    
    public static function get_type_id($name)
    {
        // is there a 'thing' with this name already?
        foreach (ORM::factory('type')->where('name', '=', $name)->find_all() as $type)
        {
            return $type->id;
        }
        
        // generate a new type record
        $type = ORM::factory('type');
        $type->name = $name;
        $type->save();
        
        return $type->pk();
    }
}