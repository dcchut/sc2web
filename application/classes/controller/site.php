<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Site extends Controller_Template {
    public $template     = 'site/template';
    public $auto_render  = TRUE;
    public $request      = NULL;
    private $title       = 'nitrated - ';
    protected $subtitle  = 'falcon eagle';
    
    public function before()
    {
        $return = parent::before();
        
        // do what we ant to do now
        $this->template->main  = '';

         
        $this->request = Request::instance();
        
        return $return;
    }
    
    public function after()
    {
        $this->template->title = $this->title . $this->subtitle;
        
        $p = Profiler::application();
        $this->template->exec  = round($p['current']['time'], 3);
        
        return parent::after();
    }
    
    public function action_index()
    {
        $this->template->main = View::factory('site/index');
    }
}
