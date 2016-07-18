<?php

class ControllerCommonOrderStatusAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
	}

	public function get() {
		$action = new Action('common/order_status');
		$data = $action->execute($this->registry);

		$response['order_status'] = $data['order_status'];

		$this->response->setOutput($response);
	}

}

?>
