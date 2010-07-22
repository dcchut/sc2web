<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Replay extends Controller_Site {
    
    public function action_upload()
    {
        $replay = ORM::factory('replay');
        
        $this->template->main = View::factory('replay/upload');
    }
    
    public function action_upload2()
    {
        $files = Validate::factory($_FILES);
        $files->rule('file', 'Upload::not_empty');
        
        if (!$files->check()) // file not uploaded!
        {
            $this->request->redirect('replay/upload');
        }
        
        // set the upload2 header up
        $this->template->main .= View::factory('replay/upload2/header');
        
        
        // is this a zip archive?
        $zip = Archive::factory('zip')->open(Arr::get($_FILES['file'], 'name', FALSE));
        
        if ($zip)
        {
            foreach ($zip->file_list() as $zfile)
            {
                $zr = $zip->read_file($zfile);
         
                $id = FALSE;
                
                if ($valid = Starparse::valid_replay(NULL, $zr))
                {
                    $id = ORM::factory('replay')->store($zfile, $zr)->pk();
                }
                
                $this->template->main .= View::factory('replay/upload2', array('success' => $valid,
                                                                               'id'      => $id,
                                                                               'replay'  => $zfile,));
            }
        }
        else
        {
            if (!($replay = ORM::factory('replay')->store($_FILES['file'])))
            {
                // an error occured with the upload, let the user know
                $this->template->main .= 'an error occured';
            }
            else
            {
                // download the now uploaded file
                $this->request->response = $replay->download();
                $this->request->send_file(TRUE, $replay->filename);
            }
        }
        
        $this->template->main .= View::factory('replay/upload2/footer');
    }
    
    public function action_download($id)
    {
        // does the replay even exist?
        if (!Model_Replay::exists($id))
        {
            $this->template->main = 'an error occured';
            return;
        }
       
        // send the response to the browser
        $replay = ORM::factory('replay', $id);

        if (!($this->request->response = $replay->download()))
        {
            $this->template->main = 'an error occured';
            return;
        }
        
        $this->request->send_file(TRUE, $replay->filename);
    }
}