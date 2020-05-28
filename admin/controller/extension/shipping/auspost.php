<?php
class ControllerExtensionShippingAusPost extends Controller {
    private $error = array();

    public function index() {
        $data = $this->load->language('extension/shipping/auspost');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('auspost', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['postcode'])) {
            $data['error_postcode'] = $this->error['postcode'];
        } else {
            $data['error_postcode'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/shipping/auspost', 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('extension/shipping/auspost', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

        if (isset($this->request->post['auspost_postcode'])) {
            $data['auspost_postcode'] = $this->request->post['auspost_postcode'];
        } else {
            $data['auspost_postcode'] = $this->config->get('auspost_postcode');
        }

        if (isset($this->request->post['auspost_standard'])) {
            $data['auspost_standard'] = $this->request->post['auspost_standard'];
        } else {
            $data['auspost_standard'] = $this->config->get('auspost_standard');
        }

        if (isset($this->request->post['auspost_express'])) {
            $data['auspost_express'] = $this->request->post['auspost_express'];
        } else {
            $data['auspost_express'] = $this->config->get('auspost_express');
        }

        if (isset($this->request->post['auspost_display_time'])) {
            $data['auspost_display_time'] = $this->request->post['auspost_display_time'];
        } else {
            $data['auspost_display_time'] = $this->config->get('auspost_display_time');
        }

        if (isset($this->request->post['auspost_weight_class_id'])) {
            $data['auspost_weight_class_id'] = $this->request->post['auspost_weight_class_id'];
        } else {
            $data['auspost_weight_class_id'] = $this->config->get('auspost_weight_class_id');
        }

        $this->load->model('localisation/weight_class');

        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        if (isset($this->request->post['auspost_tax_class_id'])) {
            $data['auspost_tax_class_id'] = $this->request->post['auspost_tax_class_id'];
        } else {
            $data['auspost_tax_class_id'] = $this->config->get('auspost_tax_class_id');
        }

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['auspost_geo_zone_id'])) {
            $data['auspost_geo_zone_id'] = $this->request->post['auspost_geo_zone_id'];
        } else {
            $data['auspost_geo_zone_id'] = $this->config->get('auspost_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['auspost_status'])) {
            $data['auspost_status'] = $this->request->post['auspost_status'];
        } else {
            $data['auspost_status'] = $this->config->get('auspost_status');
        }

        if (isset($this->request->post['auspost_sort_order'])) {
            $data['auspost_sort_order'] = $this->request->post['auspost_sort_order'];
        } else {
            $data['auspost_sort_order'] = $this->config->get('auspost_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/auspost', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/shipping/auspost')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!preg_match('/^[0-9]{4}$/', $this->request->post['auspost_postcode'])) {
            $this->error['postcode'] = $this->language->get('error_postcode');
        }

        return !$this->error;
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