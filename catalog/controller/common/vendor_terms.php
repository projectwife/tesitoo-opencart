<?php
class ControllerCommonVendorTerms extends Controller {
	public function index() {
		$this->load->model('information/information');

		$result = $this->model_information_information->getVendorTerms();

		$data['vendor_terms'] = $result['vendor_terms'];

		return $data;
	}
}