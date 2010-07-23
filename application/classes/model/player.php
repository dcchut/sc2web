<?php defined('SYSPATH') or die('No direct script access.');

class Model_Player extends ORM {
    protected $_has_many = array('replays' => array('model'   => 'replay',
                                                    'through' => 'players_replays'),
                                 'races'   => array('model'   => 'race',
                                                    'through' => 'players_replays'));
    
    public static function get_player_id($name)
    {
        $cache     = Cache::instance('xcache');
        $cache_key = 'model/player/get_player_id/' . $name;
        
        if (!($player_id = $cache->get($cache_key, FALSE)))
        {
            // is there a map already with this name?
            foreach (ORM::factory('player')->where('name', '=', $name)->find_all() as $player)
            {
                return $player->id;
            }
            
            // generate a new map record
            $player = ORM::factory('player');
            $player->name = $name;
            $player->save();
        
            $cache->set($cache_key, ($player_id = $player->pk()));
        }
        
        return $player_id;
    }
}