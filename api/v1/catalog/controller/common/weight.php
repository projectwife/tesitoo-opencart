<?php

class ControllerCommonWeightAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
	}

	public function get() {
		$action = new Action('common/weight');
		$data = $action->execute($this->registry);

		$response['weight_units'] = $data['weight_units'];

		$this->response->setOutput($response);
	}

}

?>