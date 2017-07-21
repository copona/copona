<?php
class ControllerStartupRouter extends Controller
{
    public function index()
    {
        if (isset($this->request->get['route']) && $this->request->get['route'] != 'action/route') {
            return new \Copona\System\Engine\Action($this->request->get['route']);
        } else {
            return new \Copona\System\Engine\Action($this->config->get('install.action_default'));
        }
    }
}