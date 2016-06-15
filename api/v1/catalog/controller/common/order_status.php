<?php

class ControllerCommonOrderStatusAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
	}

	public function get() {
		$action = new Action('common/Order_status');
		$data = $action->execute($this->registry);

		$response['order-status'] = $data['order_status'];

		$this->response->setOutput($response);
	}

}

?>
