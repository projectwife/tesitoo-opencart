<?php
class ControllerCommonLength extends Controller {
	public function index() {
		$this->load->language('common/length');

		$this->load->model('localisation/length');

		$data['length_units'] = array();

		$results = $this->model_localisation_length->getLengthDescriptions();

		foreach ($results as $result) {
			$data['length_units'][] = array(
				'length_class_id'   => $result['length_class_id'],
				'language_id'       => $result['language_id'],
				'title'             => $result['title'],
				'unit'              => $result['unit']
				);
		}

		return $data;
	}
}