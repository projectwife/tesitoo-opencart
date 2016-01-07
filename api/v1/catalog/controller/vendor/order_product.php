<?php


class ControllerVendorOrderProductAPI extends ApiController {

	public function index($args = array()) {

		//must be logged in to see list of orders - probably still not tight enough security here
		if ($this->user->isLogged()) {

			if (isset($args['id'])) {
				if ($this->request->isGetRequest()) {
					return $this->response->setOutput($this->getVendorOrderProduct($args['id']));
				} elseif ($this->request->isPostRequest()) {
					return $this->response->setOutput($this->editVendorOrderProduct($args['id']));
				}
			}
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}
	}

	//Get the products for a specific order belonging to this vendor
	protected function getVendorOrderProduct($id) {

		$this->load->model('sale/vdi_order');

		$orders = $this->model_sale_vdi_order->getOrderProduct($id);

		return $orders;
	}

	protected function editVendorOrderProduct($id) {

		$this->load->model('sale/vdi_order');
		$order_product = $this->model_sale_vdi_order->getOrderProduct($id);

		if (isset($this->request->post['order_status_id'])) {
			$order_product['order_status_id'] = (int)$this->request->post['order_status_id'];
		}

		$orders = $this->model_sale_vdi_order->editOrderProduct($id, $order_product);
	}
}
