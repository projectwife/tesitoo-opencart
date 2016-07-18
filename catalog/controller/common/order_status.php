<?php
class ControllerCommonOrderStatus extends Controller {
	public function index() {
		$this->load->language('common/order_status');

		$this->load->model('localisation/order_status');

		$data['order_status'] = array();

		$results = $this->model_localisation_order_status->getOrderStatus();

		foreach ($results as $result) {
			$data['order_status'][] = array(
				'order_status_id'  => $result['order_status_id'],
				'language_id'      => $result['language_id'],
				'name'		   => $result['name'],		
				);
		}

		return $data;
	}
}
