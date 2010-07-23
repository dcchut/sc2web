<?php defined('SYSPATH') or die('No direct script access.');

class Prettylink_Replay extends Prettylink {
    public static function text($id)
    {
       if (!Model_Replay::exists($id))
           return 'falcon_punch';
        
       return ORM::factory('replay', $id)->title();
    }
}