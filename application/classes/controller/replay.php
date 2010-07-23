<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Replay extends Controller_Site {
    /**
     *  Form allowing a user to upload a replay to the repository
     */
    public function action_upload()
    {
        $this->subtitle = 'upload a replay';

        $replay = ORM::factory('replay');
        
        $this->template->main = View::factory('replay/upload');
    }
    
    /**
     * Handle the technical details of uploading a replay
     */
    public function action_upload2()
    {
        $this->subtitle = 'replay(s) uploaded';
        $this->template->main .= View::factory('replay/upload2/header');
        
        /*
         * Has the file been submitted correctly?
         */
        $files = Validate::factory($_FILES);
        $files->rule('file', 'Upload::not_empty');
        
        if (!$files->check()) // file not uploaded!
        {
            $this->request->redirect('replay/upload');
        }
        
        /*
         * Are we uploading a MASS of ZIP files?
		 */
        if (($zip = Archive::factory('zip')->open($_FILES['file']['tmp_name'], FALSE, $_FILES['file']['name'])) !== FALSE)
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
                                                                               'id'      => (int)$id,
                                                                               'replay'  => Text::limit_chars($zfile, 50)));
            }
        }
        else
        {
            /*
             * Just a single replay upload - validation it as per normal
             */
            $id = FALSE;
            
            // is this a valid replay?
            if ($valid = Starparse::valid_replay($_FILES['file']['tmp_name']))
            {
                $id = ORM::factory('replay')->store($_FILES['file']);
            }
            
            $this->template->main .= View::factory('replay/upload2', array('success' => $valid,
                                                                           'id'      => (int)$id,
                                                                           'replay'  => htmlentities($_FILES['file']['name'])));
        }
        
        $this->template->main .= View::factory('replay/upload2/footer');
    }
    
    /**
     * Download a replay (throws the replay at the user)
     * @param integer $id replay ID
     * @return void
     */
    public function action_download($id)
    {
        // does the replay even exist?
        if (!Model_Replay::exists($id))
            return ($this->template->main = 'an error occured');
       
        // send the response to the browser
        $replay = ORM::factory('replay', $id);

        if (!($this->request->response = $replay->download()))
        {
            $this->template->main = 'an error occured';
            return;
        }
        
        $this->request->send_file(TRUE, $replay->filename);
    }
    
    /**
     * Display a replay with some details about it
     * @param integer $id
     * @return string
     */
    public function action_view($id)
    {
        if (!Model_Replay::exists($id))
            return ($this->template->main = 'an error occured');
        
        // get details about this replay
        $replay = ORM::factory('replay', $id);
        
        $this->subtitle       = 'viewing replay: ' . htmlentities(pathinfo($replay->filename, PATHINFO_FILENAME));
        $this->template->main = View::factory('replay/view', array('replay'    => $replay->as_array(),
                                                                   'players'   => $replay->players(),));
    }
}->players(),));
    }
}   }
}