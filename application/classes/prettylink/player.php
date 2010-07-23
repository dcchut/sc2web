<?php defined('SYSPATH') or die('No direct script access.');

class Prettylink_Player extends Prettylink {
    public static function text($id)
    {
       if (!Model_Player::exists($id))
           return 'falcon_punch';
        
       return ORM::factory('player', $id)->name;
    }
}