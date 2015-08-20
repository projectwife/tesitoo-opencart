<?php
class ControllerApiSetting extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('api/setting'); 

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('api', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('api/setting', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit'); 

		$data['entry_access_token_ttl'] = $this->language->get('entry_access_token_ttl');

		$data['help_access_token_ttl'] = $this->language->get('help_access_token_ttl');

		$data['button_save'] = $this->language->get('button_save');

		$data['tab_oauth'] = $this->language->get('tab_oauth');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['access_token_ttl'])) {
			$data['error_access_token_ttl'] = $this->error['access_token_ttl'];
		} else {
			$data['error_access_token_ttl'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('api/setting', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('api/setting', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['api_access_token_ttl'])) {
			$data['api_access_token_ttl'] = $this->request->post['api_access_token_ttl'];
		} else {
			$data['api_access_token_ttl'] = $this->config->get('api_access_token_ttl');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('api/setting.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'api/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['api_access_token_ttl']) == 0) || (utf8_strlen($this->request->post['api_access_token_ttl']) > 10)) {
			$this->error['access_token_ttl'] = $this->language->get('error_access_token_ttl');
		}

		return !$this->error;
	}

}
?>