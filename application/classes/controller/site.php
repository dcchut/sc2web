<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Site extends Controller_Template {
    /**
     * Automatically render the template
     * @var bool
     */
    public $auto_render  = TRUE;

    /**
     * Default template to render
     * @var string
     */
    public $template     = 'site/template';
   
    /**
     * Site TITLE, cannot be changed by child controllers
     * @var unknown_type
     */
    private   $title     = 'nitrated - ';
    
    /**
     * Tagline to appear after the title in the HTML TITLE element
     * @var string
     */
    protected $subtitle  = 'falcon eagle';
    
    /**
     * Cache the current request (done on a per/URI basis) - requires auto rendering
     * @var bool
     */
    protected $cache = FALSE;
    
    /**
     * Cache key used to identify the cache object, cannot be changed by child controllers
     * @var string
     */
    private   $cache_key = '';
    
    /**
     * Duration of the page cache
     * @var integer
     */
    protected $cache_duration = 600;
    
    /* (non-PHPdoc)
     * @see system/classes/kohana/controller/Kohana_Controller_Template#before()
     */
    public function before()
    {
        $return = parent::before();

        if ($this->auto_render)
        {
            $this->template->main = '';
            
            // here we take care of checking if we can use the cache
            $this->cache_key = Cache::key(Request::current()->uri);
            
            if (($main = Cache::instance('default')->get($this->cache_key, FALSE)) !== FALSE)
            {
                $main = unserialize($main);
                
                // do this like the bootstrap would
                $this->template->main = $main[0];
                $this->subtitle       = $main[1];
                
                $this->after();
                
                echo $this->request->response;
                
                // we stop it here, otherwise we'd be seeing double
                exit();
            }
        }

        if ($this->auto_render)
            $this->template->main  = '';
        
        $this->request = Request::instance();
        
        return $return;
    }
    
    /* (non-PHPdoc)
     * @see system/classes/kohana/controller/Kohana_Controller_Template#after()
     */
    public function after()
    {   
        if ($this->auto_render)
        {
            $this->template->title = $this->title . $this->subtitle;
            
            $p = Profiler::application();
            
            $this->template->exec  = round($p['current']['time'], 3);
        }
        
        $return = parent::after();
        
        // cache that motherfunker
        if ($this->auto_render && $this->cache)
        {
            Cache::instance('default')->set($this->cache_key, serialize(array((string)$this->template->main, $this->subtitle)), $this->cache_duration);
        }
        
        return $return;
    }
    
    /**
     * Our humble site index
     */
    public function action_index()
    {
        $this->template->main = View::factory('site/index');
    }
}
