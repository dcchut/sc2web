<?php defined('SYSPATH') or die('No direct script access.');

class Model_Map extends ORM {
    protected $_belongs_to = array('replay' => array());
    
    public static function get_map_id($name)
    {
        // is there a map already with this name?
        foreach (ORM::factory('map')->where('name', '=', $name)->find_all() as $map)
        {
            return $map->id;
        }
        
        // generate a new map record
        $map = ORM::factory('map');
        $map->name = $name;
        $map->save();
        
        return $map->pk();
    }
}