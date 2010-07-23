<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Archive_Zip {
	/**
	 * @var ZipArchive zip archive
	 */
	protected $_zip = FALSE;
	
	private function valid()
	{
	    return !($this->_zip === FALSE || !($this->_zip instanceof ZipArchive));
	}
	
	/**
	 * @param unknown_type $file_name
	 * @param unknown_type $create
	 * @return Kohana_Archive_Zip
	 */
	public function open($file_name, $create = FALSE, $real_file_name = FALSE)
	{
	    $file_name = realpath($file_name);
	    
	    // does this file exist? (and not a temporary file?)
	    if (!file_exists($file_name))
	        return FALSE;
        
	    // Do we even have the ZIP driver?
	    if (!class_exists('ZipArchive'))
	        return FALSE;
	      
        // Is it REALLY a zip file?
        if (!$create) 
        {
    		// Load all of the mime types
            $mimes     = Kohana::config('mimes');
            
            // what are the zip MIME types?
            $zip_mimes = isset($mimes['zip']) ? $mimes['zip'] : array('application/zip');
            
            // can we get the mime type from the file itself?
            if (($mime = File::mime($file_name)) !== FALSE && !in_array($mime, $zip_mimes))
                return FALSE;
                
            // what extension is the submitted file?
            if ($real_file_name !== FALSE)
            {
                $extension = strtolower(pathinfo($real_file_name, PATHINFO_EXTENSION));
                
                if (($mime = File::mime_by_ext($extension)) !== FALSE)
                {
                    if (!in_array($mime, $zip_mimes))
                        return FALSE;
                }
                else
                {
                    // what the fuck is this file?
                    return FALSE;
                }
            }
        }
	        
	        
	    // are we creating an archive or opening an existing one?
	    $flags = ($create) ? ZIPARCHIVE::CREATE : ZIPARCHIVE::CHECKCONS;

	    try
	    {
    	    $this->_zip = new ZipArchive;
    	    
	        if ($this->_zip->open($file_name, $flags) === TRUE);
	            return $this;
	    }
	    catch (Exception $ex) // something bad happened!, return false
	    { }
	    
	    return FALSE;
	}
	
	/**
	 * @return string
	 */
	public function close()
	{
	    // are we even open?
	    if ($this->valid())
	        $this->_zip->close();
	        
        return TRUE;
	}
	
	public function read_file($file_name)
	{
	    if (!$this->valid())
	        return FALSE;
        
        if (!($output = $this->_zip->getFromName($file_name)))
            return FALSE;
            
       return $output;
	}
	
	public function file_list()
	{
	    $fl = array();
	    
	    for ($i = 0; $i < $this->_zip->numFiles; $i++)
	    {
	        $fl[] = $this->_zip->getNameIndex($i);
	    }
	    
	    return $fl;
	}
}