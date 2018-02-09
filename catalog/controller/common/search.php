<?php
class ControllerCommonSearch extends Controller {

    public function index() {
        $data = $this->load->language('common/search');

        if (isset($this->request->get['search'])) {
            $data['search'] = $this->request->get['search'];
        } else {
            $data['search'] = '';
        }

        return $this->load->view('common/search', $data);
    }

}