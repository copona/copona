<?php
class ControllerApiAjax extends Controller {

    function getFeaturedProducts() {

        prd($this->config->get('featured_product'));

        $data['products'] = array();
        if ($data['products']) {

            $this->response->setOutput($this->load->view('extension/module/featured', $data));
        }
    }

    /*
     * Ajax method for Product ajax requests.
     * Method can be redirected, using POST "route" variable. But this is done then in Hook.
     */

    public function product() {
        $data = [ ];
        $data['success'] = true;
        $this->hook->getHook('api/ajax/product', $data);
        $this->response->setOutput(json_encode($data));
    }

}