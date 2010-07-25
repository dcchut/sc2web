<?php defined('SYSPATH') or die('No direct script access.');

class Prettylink_Replay extends Prettylink {
    public static function text($id)
    {
       if (!Model_Replay::exists($id))
           return 'falcon_punch';
        
        // get our replay instance
        $replay = ORM::factory('replay', $id);
       	$players = $replay->players->find_all()->as_array();
    
    	// playing a 1v1, format it nicely
    	if (count($players) == 2)
    	{
    		return $players[0]->name . ' (' . Model_Race::get_match_race($players[0]->id, $id)->short_name . ')' .
    		       ' vs ' 		 . 
    		       $players[1]->name . ' (' . Model_Race::get_match_race($players[1]->id, $id)->short_name . ')' .
    		       ' on '            . 
    		       $replay->map->name  .  
    		       date(' (d/m/Y)', $replay->upload_date);
    	}

    	// we do something silly for now - fix this for 2v2 and 3v3
    	return $replay->type->name . ' on ' . $replay->map->name . date(' (d/m/Y)', $replay->upload_date);
    }
}