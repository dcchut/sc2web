<?php defined('SYSPATH') or die('No direct script access.');

class Model_Replay extends ORM {
    
    protected $_has_many = array('players' => array('model'   => 'player',
                                                    'through' => 'players_replays'),
                                 'races'   => array('model'   => 'race',
                                                    'through' => 'players_replays'));
    
    protected $_has_one  = array('maps' => array());
    
    /**
     * @var string Local location to store replays
     */
    protected $replay_dir = 'upload\replays';
    
    protected function local_filename()
    {
        return $this->pk() . '.r';
    }
    
    protected function local_path()
    {
        return realpath($this->replay_dir) . DIRECTORY_SEPARATOR . $this->local_filename();
    }
    
    /**
     * Download the replay
     * @return string contains the replay file in string form
     */
    public function download()
    {
        // get the directory to the local file
        $filename = $this->local_path();
        
        // increment the download counter
        $this->downloaded++;
        $this->save();

        // perhaps in future implement some sort of file-cache in-memory?
        $cache     = Cache::instance('xcache');
        $cache_key = 'model/replay/' . $this->pk() . '/download';
        
        if ($data = $cache->get($cache_key, FALSE))
            return $data;
        
        // now we cache the file, if it still exists(?)
        if (!file_exists($filename))
        {
            Kohana_Log::instance()->add('Error',
                                        'File not found (:file)',
                                        array(':file' => $this->filename));
            
            return FALSE;
        }
        

        $dl = file_get_contents($filename);
        
        // cache the file
        $cache->set($cache_key, $dl);
        
        return $dl;
    }
    
    /**
     * Store a single replay file in the database
     * @param FILE $file
     * @return ORM or FALSE
     */
    public function store($file, $string = NULL)
    {
        if (!is_null($string))
        {
            $hash     = sha1($string);
            $filename = substr($file, 0, 200);
            $map      = Model_Map::get_map_id(Starparse::get_map(FALSE, $string));
            $players  = Starparse::get_players(FALSE, $string);
        }
        else
        {
            // ensure the uploaded file is valid
            if (!Upload::valid($file))
                return FALSE;

            $hash     = sha1_file($file['tmp_name']);
            $filename = substr($file['name'], 0, 200);
            $map      = Model_Map::get_map_id(Starparse::get_map($file['tmp_name']));
            $players  = Starparse::get_players($file['tmp_name']);
        }

        // check if this replay is already in the database
        if (($replay = $this->clear()->where('hash', '=', $hash)->find()) && !$replay->empty_pk())
            return $replay;    // not sure how to handle this case yet
            
        // this file hasn't been uploaded, so insert a record
        $this->clear();
        $this->filename    = $filename;
        $this->upload_date = time();
        $this->user_id     = 1;
        $this->downloaded  = 0;
        $this->map_id      = $map;
        
        $this->hash        = $hash;
        
        if (!$this->save())
            return FALSE;
            
        /* 
         * Add the appropriate records to the through table for each player (cannot be done through ORM, unfortunately)
         */
        foreach ($players as $player)
        {
            $columns = array('replay_id', 'player_id', 'race_id');
            $values  = array($this->pk(), Model_Player::get_player_id($player[0]), Model_Race::get_race_id($player[1]));
            
            DB::insert('players_replays')
                ->columns($columns)
                ->values($values)
                ->execute($this->_db);
        }
        
        if (!is_null($string))
        {
            // save the string to a file
            file_put_contents(realpath($this->replay_dir) . DIRECTORY_SEPARATOR . $this->local_filename(), $string);
        }
        else
        {
            // save the upload
            if (!Upload::save($file, $this->local_filename(), $this->replay_dir))
                return FALSE;
        }
        
        return $this;
    }
    
    protected function players_cache_id()
    {
        return $this->cache_id() . '/players';
    }
    
    public function players($ignore = FALSE)
    {
        // our resultant players list
        $cache     = Cache::instance('xcache');
        $cache_key = 'model/replay/' . $this->pk() . '/players';
        
        if (!($result = $cache->get($cache_key, FALSE)))
        {
            // get all of the appropriate details (name & race)
            $result = DB::select('player_id', 'race_id')
                        ->from('players_replays')
                        ->where('replay_id', '=', $this->id)
                        ->execute($this->_db);

            $cache->set($cache_key, serialize($result));
        }
        else
            $result = unserialize($result);
        
        
        foreach ($result as $through)
        {   
            if ($through['player_id'] == $ignore)
                continue;
                
            $player = ORM::factory('player', $through['player_id']);
            $race   = ORM::factory('race', $through['race_id']);
            
            $return = new stdClass;
            $return->player = $player;
            $return->race   = $race;
            
            $players[] = $return;
        }
        

        return $players;
    }
    
    public function opponents($player_id)
    {
           // get the opponents of this replay
           $opponents      = $this->players($player_id);
          
           $opponents_text = '';
           $opponents_c    = count($opponents);
           
           $i = 0;
           
           foreach ($opponents as $r)
           {
               $opponents_text .= $r->player->name;
               $opponents_text .= ($i == $opponents_c - 2) ? ' and ' : ', ';
               
               $i++;
           }

           $opponents_text = substr($opponents_text, 0, -2);

           return $opponents_text;
    }
}

