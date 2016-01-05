<?php
class ControllerCommonWeight extends Controller {
	public function index() {
		$this->load->language('common/weight');

		$this->load->model('localisation/weight');

		$data['weight_units'] = array();

		$results = $this->model_localisation_weight->getWeightDescriptions();

		foreach ($results as $result) {
			$data['weight_units'][] = array(
				'weight_class_id'   => $result['weight_class_id'],
				'language_id'       => $result['language_id'],
				'title'             => $result['title'],
				'unit'              => $result['unit']
				);
		}

		return $data;
	}
}