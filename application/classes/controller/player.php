<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Player extends Controller_Site {
    /**
     * View a particular players 'things'
     * @param integer $id internal ID of the player
     * @return string
     */
    public function action_view($id)
    {
        // this is an expensive method for players with a lot of replays, we should cache the shit out of it
        $this->cache = TRUE;
         
        if (!Model_Player::exists($id))
            return ($this->template->main = 'an error occured');

        // display some general details of the player
        $player				  = ORM::factory('player', $id);
        $this->subtitle       = 'viewing ' . htmlentities($player->name) .'\'s profile';
         
        $this->template->main = View::factory('player/view/header', array('player'     => $player->as_array(),
                                                                          'downloaded' => $player->downloaded(),));

        // display details on each replay that this player has been involved in
        foreach ($player->replays->find_all() as $replay)
        {
            $this->template->main .= View::factory('player/view', array('race'        => htmlentities(Model_Race::get_match_race($player->id,
                                                                                                                                 $replay->id)->name),
                                                                        'replay_uri'  => Prettylink::uri('replay', $replay->id),
                                                                        'replay_text' => htmlentities($replay->title()),
                                                                        'opponents'   => htmlentities($replay->opponents_text($player->id)),));
        }
         
        $this->template->main .= View::factory('player/view/footer');
    }
}