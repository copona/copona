<?php
class ControllerExtensionShippingLocationBasedShipping extends Controller {
    private $error;

    public function index() {

        $data = $this->load->language('extension/shipping/location_based_shipping');

        $this->load->model('setting/setting');
        $this->load->model('localisation/geo_zone');
        $this->load->model('localisation/tax_class');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $costs = array();
            foreach ($this->request->post['location_based_shipping_cost'] as $cost) {
                $costs[$cost['group']][] = $cost;
            }
            $postData = array(
                'location_based_shipping_status'     => $this->request->post['location_based_shipping_status'],
                'location_based_shipping_sort_order' => $this->request->post['location_based_shipping_sort_order'],
                'location_based_shipping_cost'       => $costs,
            );

            $this->model_setting_setting->editSetting('location_based_shipping', $postData);

            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
        }

        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');

        $data['action'] = $this->url->link('extension/shipping/location_based_shipping', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);
        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_shipping'),
            'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/shipping/location_based_shipping', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $postKeyArray = array( 'location_based_shipping_status', 'location_based_shipping_sort_order',
            'location_based_shipping_cost' );
        foreach ($postKeyArray as $key) {
            if (isset($this->request->post['location_based_shipping_status'])) {
                $data[$key] = $this->request->post[$key];
            } else {
                $data[$key] = $this->config->get($key);
            }
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/location_based_shipping.tpl', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/shipping/location_based_shipping')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function install() {
        // bulk OC3 design install function
        return 0;
    }

    public function uninstall() {
        // bulk OC3 design install function
        return 0;
    }

}