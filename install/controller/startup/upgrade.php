<?php

class ControllerStartupUpgrade extends Controller
{
    public function index()
    {
        $upgrade = false;

        if (is_file(DIR_COPONA . '.env') && filesize(DIR_COPONA . '.env') > 0) {
            $upgrade = true;
        }

        if (isset($this->request->get['route'])) {
            if (($this->request->get['route'] == 'install/step_4') || (substr($this->request->get['route'], 0, 8) == 'upgrade/') || (substr($this->request->get['route'], 0, 10) == '3rd_party/')) {
                $upgrade = false;
            }
        }

        if ($upgrade) {
            //TODO
            die('.env already exists with a size &gt; 0. Please, consider upgrade manually.');
            $this->response->redirect($this->url->link('upgrade/upgrade'));
        }
    }
}