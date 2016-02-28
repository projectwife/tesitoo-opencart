<?php

class ControllerCommonLengthAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
	}

	public function get() {
		$action = new Action('common/length');
		$data = $action->execute($this->registry);

		$response['length_units'] = $data['length_units'];

		$this->response->setOutput($response);
	}

}

?>