<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana Cache Null Driver
 * 
 */
class Kohana_Cache_Null extends Cache {
	public function get($id, $default = NULL)
	{
		return FALSE;
	}
	public function set($id, $data, $lifetime = NULL)
	{
		return FALSE;
	}

	public function delete($id)
	{
		return FALSE;
	}
	
	public function delete_all()
	{
		RETURN FALSE;
	}
}