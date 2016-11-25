<?php
class ControllerCommonFooter extends Controller {

	public function index() {
		$this->load->language('common/footer');
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['text_footer'] = $this->language->get('text_footer');

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), VERSION);
		} else {
			$data['text_version'] = '';
		}

		return $this->load->view('common/footer', $data);
	}

}