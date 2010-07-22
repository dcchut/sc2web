<?php defined('SYSPATH') or die('No direct script access.');

class Model_Replay extends ORM {
    
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

    protected function cache_id()
    {
        return 'file_replay_' . $this->pk();
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
        $cache = Cache::instance('xcache');
        
        if ($data = $cache->get($this->cache_id(), FALSE))
            return $data;
        
        // now we cache the file, if it still exists(?)
        if (!file_exists($filename))
        {
            Kohana_Log::instance()->add('Error',
                                        'File not found (:file)',
                                        array(':file' => $this->filename));
            
            return FALSE;
        }
        
        // cache the file
        $fdata = file_get_contents($filename);
        
        if (!$cache->set($this->cache_id(), $fdata))
        {
            Kohana_Log::instance()->add('ERROR', 
            							'Could not cache replay (:replay)', 
                                        array(':replay' => $this->pk(),));
        }
        
        return $fdata;
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
        }
        else
        {
            // ensure the uploaded file is valid
            if (!Upload::valid($file))
                return FALSE;

            $hash     = sha1_file($file['tmp_name']);
            $filename = substr($file['name'], 0, 200);
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
        $this->hash        = $hash;
        
        if (!$this->save())
            return FALSE;
            
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
}