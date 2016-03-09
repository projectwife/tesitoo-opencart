<?php

class ControllerCheckoutApiPay extends Controller { 
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$data['icon'] = '';
		}

		$data['payment'] = $this->load->controller('payment/' . $this->session->data['payment_method']['code']);
		$data['autosubmit'] = $this->session->data['autosubmit'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/apipay.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/apipay.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/checkout/apipay.tpl', $data));
		}
	}
}
?>