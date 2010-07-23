<?php defined('SYSPATH') or die('No direct script access.');

class Prettylink_Replay extends Prettylink {
    public static function text($id)
    {
       if (!Model_Replay::exists($id))
           return 'falcon_punch';
        
       return pathinfo(ORM::factory('replay', $id)->filename, PATHINFO_FILENAME);
    }
}