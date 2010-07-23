<?php defined('SYSPATH') or die('No direct script access.');

class Model_Race extends ORM {
    protected $_has_many = array('replays' => array(),
                                 'players' => array());
    
    public static function get_race_id($name)
    {
        // is there a map already with this name?
        foreach (ORM::factory('race')->where('name', '=', $name)->find_all() as $race)
        {
            return $race->id;
        }
        
        // no race id - don't insert any more
        return FALSE;
    }

    public static function get_match_race($player_id, $replay_id)
    {
        // what cache key are we gonna use here?
        $cache     = Cache::instance('xcache');
        $cache_key = 'model/race/get_match_race/' . $player_id . '/' . $replay_id;
        
        if (!($race = $cache->get($cache_key, FALSE)))
        {
            // first find the race ID
            $race_id = DB::select('race_id')
                        ->from('players_replays')
                        ->where('replay_id', '=', $replay_id)
                        ->where('player_id', '=', $player_id)
                        ->execute()
                        ->get('race_id');
                        
            $race = ORM::factory('race', $race_id);
            $cache->set($cache_key, serialize($race));
        }
        else
            $race = unserialize($race);

        return $race;
    }
}