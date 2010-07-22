<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Archive {
	/**
	 * Create a new instance of an Archive object
	 * @param string $name driver name
	 * @return Kohana_Archive
	 */
	public static function factory($name)
	{
	    $driver = 'Archive_' . ucfirst($name);
	    
	    return new $driver();
	}
	
	/**
	 * Open an archive (load/create)
	 * @param string $filename target filename
	 */
	public abstract function open($file_name);
	
	/**
	 * Close the archive (saving all changes made)
	 */
	public abstract function close();
	
	/**
	 * Read a file from within the archive with the given filename
	 * @param string $filename target filename
	 */
	public abstract function read_file($file_name);
	
	/**
	 * List of all files within the archive
	 */
	public abstract function file_list();
}