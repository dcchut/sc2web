<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Site extends Controller_Template {
    public $template    = 'site/template';
    public $auto_render = TRUE;
    public $request     = NULL;
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
        
        return parent::after();
    }
    
    protected function _view_text($id)
    {
        return '';
    }
    
    public function view_uri($id, $view = 'view')
    {
        return $this->request->controller . '/view/' . (int)$id . '/' . str_replace(' ', '-', $this->_view_text($id));
    }
} // End Site
