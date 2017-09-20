<?php
class ControllerExtensionShippingWeight extends Controller {
    private $error = array();

    public function index() {
        $data = $this->load->language('extension/shipping/weight');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            //prd($this->request->post);
            $this->model_setting_setting->editSetting('weight', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
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
            'href' => $this->url->link('extension/shipping/weight', 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('extension/shipping/weight', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

        $this->load->model('localisation/geo_zone');

        $geo_zones = $this->model_localisation_geo_zone->getGeoZones();

        $this->load->model('localisation/language');

        $languages = $this->model_localisation_language->getLanguages();
        $data['languages'] = $languages;
        //pr($this->request->post);
        // pr($data['languages']);
        foreach ($geo_zones as $geo_zone) {
            foreach ($languages as $language) {
                $data["weight_" . $language['language_id'] . "_" . $geo_zone['geo_zone_id'] . "_display"] = !empty(
                        $this->request->post["weight_" . $language['language_id'] . "_" . $geo_zone['geo_zone_id'] . "_display"]) ?
                    $this->request->post["weight_" . $language['language_id'] . "_" . $geo_zone['geo_zone_id'] . "_display"] :
                    $this->config->get("weight_" . $language['language_id'] . "_" . $geo_zone['geo_zone_id'] . "_display");
            }

            //prd($data);
            if (isset($this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_rate'])) {
                $data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_rate'];
            } else {
                $data['weight_' . $geo_zone['geo_zone_id'] . '_rate'] = $this->config->get('weight_' . $geo_zone['geo_zone_id'] . '_rate');
            }

            if (isset($this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_status'])) {
                $data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->post['weight_' . $geo_zone['geo_zone_id'] . '_status'];
            } else {
                $data['weight_' . $geo_zone['geo_zone_id'] . '_status'] = $this->config->get('weight_' . $geo_zone['geo_zone_id'] . '_status');
            }
        }

        //prd($data);

        $data['geo_zones'] = $geo_zones;
        $data['languages'] = $languages;
        if (isset($this->request->post['weight_tax_class_id'])) {
            $data['weight_tax_class_id'] = $this->request->post['weight_tax_class_id'];
        } else {
            $data['weight_tax_class_id'] = $this->config->get('weight_tax_class_id');
        }

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['weight_status'])) {
            $data['weight_status'] = $this->request->post['weight_status'];
        } else {
            $data['weight_status'] = $this->config->get('weight_status');
        }

        if (isset($this->request->post['weight_sort_order'])) {
            $data['weight_sort_order'] = $this->request->post['weight_sort_order'];
        } else {
            $data['weight_sort_order'] = $this->config->get('weight_sort_order');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/weight', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/shipping/weight')) {
            $this->error['warning'] = $this->language->get('error_permission');
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