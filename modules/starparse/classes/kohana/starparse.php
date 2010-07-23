<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Starparse {
    /**
     * Get the temp directory to store files
     * @return Ambigous <string, Kohana_Config>
     */
    protected static function _tmp_dir()
    {
        $starparse = Kohana::config('starparse');
        
        return isset($starparse['tmp_dir']) ? $starparse['tmp_dir'] : '.';
    }
    
    /**
     * Use the readfile command to read a given file within a MPQ archive
     * @param string $archive_name the MPQ archive in question
     * @param string $internal_file_name the file within the archive to return
     * @return string
     */
    protected static function readfile($archive_name, $internal_file_name)
    {
        $cmd = "readfile " . escapeshellarg($archive_name) . " " . escapeshellarg($internal_file_name) ." q";

        // catch the output of this command
        ob_start(); {
            passthru($cmd);
        } $ret = ob_get_clean();
        
        if ($ret === 'error')
            return FALSE;
       
        return $ret;
    }
    
    /*
     * 	preg_match_all(, $replay_details, $matches);
	var_dump($matches[1]);
	var_dump($matches[2]);
     */
    
    /*
     * implement 3-way-many to many relationship (within ORM)
     */
    public static function get_players($file_name, $string = NULL, $delete = FALSE)
    {
        if (!self::valid_replay($file_name, $string))
        {
            return FALSE;
        }    
        
        if (!is_null($string))
        {
            $tmp = tempnam(self::_tmp_dir(), 'TMP');
            file_put_contents($tmp, $string);
            return self::get_players($tmp, NULL, TRUE);
        }
        
        $archive_name = realpath($file_name);
        
        $matches        = array();
        $replay_details = self::readfile($file_name, 'replay.details');
        $replay_details = str_replace(array("\r\n"), array("\100"), $replay_details); // for now
        
        preg_match_all("/\002.(\w*?)\002\005.*?(Zerg|Terran|Protoss)\006/i", $replay_details, $matches);

        if ($delete)
            @unlink($file_name);

        $c = min(count($matches[1]), count($matches[2]));
        $r = array();
        
        for ($i = 0; $i < $c; $i++)
        {
            $r[] = array($matches[1][$i], $matches[2][$i]);
        }

        return $r;
    }
    
    public static function get_map($file_name, $string = NULL, $delete = FALSE)
    {
        if (!self::valid_replay($file_name, $string))
        {
            return FALSE;
        }    
        
        if (!is_null($string))
        {
            $tmp = tempnam(self::_tmp_dir(), 'TMP');
            file_put_contents($tmp, $string);
            return self::get_map($tmp, NULL, TRUE);
        }
        
        $archive_name = realpath($file_name);
        
        $matches        = array();
        $replay_details = self::readfile($file_name, 'replay.details');
        
        preg_match("/\002{2}.(\w.*?)\004/i", $replay_details, $matches);
        
        if ($delete)
            @unlink($file_name);
        
        if (isset($matches[1]))
            return $matches[1];
            
        return FALSE;
    }
    /**
     * Is a specified replay (file/string) valid?
     * @param string $file_name Replay file to check
     * @param string $string A replay in string form to check
     * @param bool $delete Delete $file_name after it is valid
     */
    public static function valid_replay($file_name, $string = NULL, $delete = FALSE)
    {
        if (!is_null($string))
        {
            $tmp = tempnam(self::_tmp_dir(), 'TMP');
            file_put_contents($tmp, $string);
            return self::valid_replay($tmp, NULL, TRUE);
        }
        

        // we must have a file name here, then
        $file_name = realpath($file_name);
        
        if (!file_exists($file_name))
            return FALSE;

        // start output buffering - catch the execution (think of a better way to do this, later)
        $listfile = self::readfile($file_name, '(listfile)', TRUE);
        
        if ($delete)
            @unlink($file_name);

        if (!$listfile)
            return FALSE;
            
        // replay.details && replay.initData
        return (strpos($listfile, 'replay.details') !== FALSE && strpos($listfile, 'replay.initData') !== FALSE);
    }
}
