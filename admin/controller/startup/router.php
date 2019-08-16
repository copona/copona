<?php
class ControllerStartupRouter extends Controller {

    public function index() {

        // Route
        if (isset($this->request->get['route']) && $this->request->get['route'] != 'startup/router') {
            $route = $this->request->get['route'];
        } else {
            $route = $this->config->get('admin.action_default');
        }

        // Sanitize the call
        $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
        $action = new \Copona\System\Engine\Action($route);

        // Any output needs to be another Action object.
        $output = $action->execute($this->registry);

        if (!is_null($output)) {
            return new \Exception('Error!');
        } else {
            return $output;
        }
    }

}