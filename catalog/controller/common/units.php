<?php
class ControllerCommonUnits extends Controller {
	public function index() {
		$this->load->language('common/units');

		$this->load->model('localisation/units');

		$data['units'] = array();

		$results = $this->model_localisation_units->getUnitDescriptions();

		foreach ($results as $result) {
			$data['units'][] = array(
				'unit_class_id'   => $result['unit_class_id'],
				'language_id'     => $result['language_id'],
				'title'           => $result['title'],
				'abbreviation'    => $result['abbreviation'],
				'note'            => $result['note']
				);
		}

		return $data;
	}
}
