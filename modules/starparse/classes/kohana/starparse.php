<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Starparse {
    
    /**
     * Get the temp directory to store files
     * @return Ambigous string
     */
    protected static function _tmp_file()
    {
        $starparse = Kohana::config('starparse');
        
        $tmp_dir = isset($starparse['tmp_dir']) ? $starparse['tmp_dir'] : sys_get_temp_dir();
        $tmp_dir = realpath($tmp_dir);
        
        return $tmp_dir . DIRECTORY_SEPARATOR . uniqid() . '.tmp';
    }
    
    /**
     * Use the readfile command to read a given file within a MPQ archive
     * @param string $archive_name the MPQ archive in question
     * @param string $internal_file_name the file within the archive to return
     * @return string
     */
    protected static function readfile($archive_name, $internal_file_name)
    {
        // what exec command are we using?
        $config = Kohana::config('starparse');
        
		// we do it through the shell.  fuck you, CGI.
		$exec = isset($config['readfile']) ? $config['readfile'] : 'readfile';
		$cmd = $exec . " " . escapeshellarg($archive_name) . " " . escapeshellarg($internal_file_name) . " q";

		if (($return = trim(shell_exec($cmd))) === 'error')
			return FALSE;
			
		return $return;
    }

    public static function get_players($file_name, $string = NULL, $delete = FALSE)
    {
        if (!self::valid_replay($file_name, $string))
        {
            return FALSE;
        }    
        
        if (!is_null($string))
        {
            // create a temporary file
            $temp_file_name = self::_tmp_file();
            file_put_contents($temp_file_name, $string);
            return self::get_players($temp_file_name, NULL, TRUE);
        }
        
        $archive_name = realpath($file_name);
        
        $matches        = array();
        $replay_details = self::readfile($file_name, 'replay.details');
        $replay_details = str_replace(array("\r\n", "\n"), array("\100\100", "\100"), $replay_details);

	    // in old patches, this found spectators, but fuck old patches, they're old
        preg_match_all("/\002.(\w+?)\002\005.*?(Zerg|Terran|Protoss)\006/i", $replay_details, $matches);

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
            $temp_file_name = self::_tmp_file();
            file_put_contents($temp_file_name, $string);
            return self::get_map($temp_file_name, NULL, TRUE);
        }
        
        $archive_name = realpath($file_name);
        
        $matches        = array();
        $replay_details = self::readfile($file_name, 'replay.details');
        
        preg_match("/\002{2}.(\w.+?)\004/i", $replay_details, $matches);
        
        if ($delete)
            @unlink($file_name);
        
        if (isset($matches[1]))
            return $matches[1];
            
        return FALSE;
    }
    
    public static function get_type($file_name, $string = NULL, $delete = FALSE)
    {
        if (!self::valid_replay($file_name, $string))
        {
            return FALSE;
        }
        
        if (!is_null($string))
        {
            $temp_file_name = self::_tmp_file();
            file_put_contents($temp_file_name, $string);
            return self::get_type($temp_file_name, NULL, TRUE);
        }
        
        $archive_name = realpath($file_name);
        
        $matches        = array();
        $replay_details = self::readfile($file_name, 'replay.attributes.events');
        
        preg_match("/\020(.{3})$/i", $replay_details, $matches);
        
        if ($delete)
            @unlink($file_name);
        
        return strrev($matches[1]); // for some reason, they're backwards
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
            $temp_file_name = self::_tmp_file();
            file_put_contents($temp_file_name, $string);
            return self::valid_replay($temp_file_name, NULL, TRUE);
        }

        // we must have a file name here, then
        $file_name = realpath($file_name);
        
        if (!file_exists($file_name))
            return FALSE;

        // is this the best way?
        $listfile = self::readfile($file_name, '(listfile)');
        $attributes = self::readfile($file_name, 'replay.attributes.events');
        
        if ($delete)
            @unlink($file_name);
            
        if (!$listfile || !$attributes || ($attributes && strlen($attributes) == 0))
            return FALSE;
            
        // replay.details && replay.initData
        return (strpos($listfile, 'replay.details') !== FALSE && strpos($listfile, 'replay.initData') !== FALSE);
    }
}
