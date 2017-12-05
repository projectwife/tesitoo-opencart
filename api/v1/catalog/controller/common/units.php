<?php

class ControllerCommonUnitsAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
	}

	public function get() {
		$action = new Action('common/units');
		$data = $action->execute($this->registry);

		$response['units'] = $data['units'];

		$this->response->setOutput($response);
	}

}

?>
