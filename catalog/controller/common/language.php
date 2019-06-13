<?php
class ControllerCommonLanguage extends Controller {

    public function index() {

        $this->load->language('common/language');

        $data['text_language'] = $this->language->get('text_language');

        $data['action'] = $this->url->link('common/language/language', '', $this->request->server['HTTPS']);

        $data['code'] = $this->session->data['language'];

        $this->load->model('localisation/language');

        $data['redirect_multilanguage'] = [];

        $url = $route = '';

        if($this->request->get('route')) {
            $url_data = $this->request->get;
            unset($url_data['_route_']);
            $route = $url_data['route'];
            unset($url_data['route']);
            $url = '';
            if ($url_data) {
                $url = '&' . urldecode(http_build_query($url_data, '', '&'));
            }

            $data['redirect'] = $this->url->link($route, $url);
        } else {
            $data['redirect'] = $this->url->link('common/home');
        }

        $language_current_code = $this->language->get('code');

        foreach ($this->model_localisation_language->getLanguages() as $key => $val) {

            $language = new Language($key);
            $this->config->set('code', $key);
            $this->session->data['language'] = $language->get('code');

            if($route) {
                $data['redirect_multilanguage'][$key] = $this->url->link($route, $url);
            } else {
                $data['redirect_multilanguage'][$key] = $this->url->link('common/home');
            }
        }

        $this->config->set('code', $language_current_code);
        $this->session->data['language'] = $language_current_code;
        $this->registry->set('language', new Language($language_current_code));
        $this->language->load($language_current_code);

        $data['languages'] = array();

        $results = $this->model_localisation_language->getLanguages();

        foreach ($results as $result) {
            if ($result['status']) {
                $data['languages'][] = array(
                  'name'      => $result['name'],
                  'code'      => $result['code'],
                  'href'      => $data['redirect_multilanguage'][$result['code']],
                  'directory' => $result['directory']
                );
            }
        }

        return $this->load->view('common/language', $data);
    }

    public function language() {
        if (isset($this->request->post['code'])) {
            $this->session->data['language'] = $this->request->post['code'];
        }

        if (isset($this->request->post['redirect'])) {
            $this->response->redirect($this->request->post['redirect']);
        } else {
            $this->response->redirect($this->url->link('common/home'));
        }
    }

}