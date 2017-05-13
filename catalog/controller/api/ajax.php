<?php
class ControllerApiAjax extends Controller {

    function getFeaturedProducts() {

        prd($this->config->get('featured_product'));

        $data['products'] = array();
        if ($data['products']) {

            $this->response->setOutput($this->load->view('extension/module/featured', $data));
        }
    }

}