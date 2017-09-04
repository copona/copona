<?php
class ControllerApiAjax extends Controller {

    function getFeaturedProducts() {

        $this->load->model('catalog/product');
        $this->load->model('tool/image');
   }

    /*
     * Ajax method for Product ajax requests.
     * Method can be redirected, using POST "route" variable. But this is done then in Hook.
     */

    public function product() {
        $data = [];
        $data['success'] = true;
        $this->hook->getHook('api/ajax/product', $data);
		$this->response->setOutput($this->load->view($data['template'], $data));
    }

}