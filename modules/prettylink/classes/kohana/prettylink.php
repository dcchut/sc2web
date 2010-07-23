<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Prettylink { 
    public static function uri($name, $id)
    {
        $class = 'Prettylink_' . ucfirst($name);
        
        return $name 
            . '/view/'
            . (int)$id
            . '/'
            . str_replace(' ', '_', $class::text($id));
    }
    
    public static function text($id)
    {
        return '';
    }
}