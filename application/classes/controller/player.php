<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Player extends Controller_Site {
    
    public function action_view($id)
    {
       if (!Model_Player::exists($id))
            return ($this->template->main = 'an error occured');
        
       $player = ORM::factory('player', $id);
       $this->subtitle       = 'viewing ' . htmlentities($player->name) .'\'s profile';
       
       $this->template->main = View::factory('player/view/header', array('player' => $player->as_array()));
       
       foreach ($player->replays->find_all() as $replay)
       {
           $this->template->main .= View::factory('player/view', array('race'  		 => htmlentities(Model_Race::get_match_race($player->id,
                                                                                                              $replay->id)->name),
                                                                       'replay_uri'  => Prettylink::uri('replay', $replay->id),
                                                                       'replay_text' => htmlentities(Prettylink_Replay::text($replay->id)),
                                                                       'opponents'   => htmlentities($replay->opponents($player->id)),));
       }
                                                            
       $this->template->main .= View::factory('player/view/footer');
    }
}