<?php
class ControllerCommonVendorTerms extends Controller {
	public function index() {

		$data['vendor_terms'] = [];

		if ($this->config->get('mvd_signup_policy_app')) {
			$this->load->model('catalog/information');
			$signup_policy = $this->model_catalog_information->getInformation($this->config->get('mvd_signup_policy_app'));
			$data['vendor_terms']['title'] = $signup_policy['title'];
			$data['vendor_terms']['description'] = $signup_policy['description'];
		}

		return $data;
	}
}
