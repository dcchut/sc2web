<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Starparse {
    
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
        } $ret = trim(ob_get_clean());
        
        if ($ret === 'error')
            return FALSE;
       
        return $ret;
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
            $tmpname = tempnam('.', 'RTP');
            file_put_contents($tmpname, $string);
            return self::valid_replay($tmpname, NULL, TRUE);
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
