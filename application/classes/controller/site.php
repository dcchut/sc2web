<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Site extends Controller_Template {
    public $template    = 'site/template';
    public $auto_render = TRUE;
    public $request     = NULL;
    
    public function before()
    {
        $return = parent::before();
        
        // do what we ant to do now
        $this->template->main = '';
        $this->request = Request::instance();
        
        return $return;
    }
} // End Site
