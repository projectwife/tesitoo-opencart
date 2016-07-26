<?php


class ControllerVendorOrderAPI extends ApiController {

	public function index($args = array()) {

		//must be logged in to see list of orders - probably still not tight enough security here
		if ($this->user->isLogged()) {

			if (isset($args['id'])) {
				if ($this->request->isGetRequest()) {
					return $this->response->setOutput($this->getVendorOrderProducts($args['id']));
				}
			}
			else {
				return $this->response->setOutput($this->getVendorOrders());
			}
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}
	}

	//Get open order list of logged in vendor
	protected function getVendorOrders() {

		$this->load->model('sale/vdi_order');
		$data = array();

        if (isset($this->request->request['filter_order_status'])) {
            //check input - should be list of integers
            if (1 === preg_match('/^[0-9,\s]+$/',
                                 $this->request->request['filter_order_status'])) {
                $data['filter_order_status'] = $this->request->request['filter_order_status'];
            }
        }

		//$data['filter_order_status'] = "1"; // Status code of orders with 'Pending' status
		$orders = $this->model_sale_vdi_order->getOrders($data);

		return $orders;
	}

	//Get the products for a specific order belonging to this vendor
	protected function getVendorOrderProducts($id) {

		$this->load->model('sale/vdi_order');

		$orders = $this->model_sale_vdi_order->getOrderProducts($id);

		return $orders;
	}
}
