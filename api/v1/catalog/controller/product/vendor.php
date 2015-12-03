<?php

require_once(DIR_API_APPLICATION . 'controller/product/base/vendor_base.php');

class ControllerProductVendorAPI extends ControllerProductVendorBaseAPI {

	public function index($args = array()) {
		parent::index($args);
	}

	public function products($args = array())
	{
		return $this->response->setOutput($this->getVendorProducts($this->getIdFromArgs($args)));
	}

	public function orders($args = array()) {
		$vendorId = $this->getIdFromArgs($args);

		if ($this->user->isLogged()) {
			return $this->response->setOutput($this->getVendorOrders($vendorId));
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}
	}

	public function order($args = array()) {
		$vendorId = $this->getIdFromArgs($args);

		if ($this->user->isLogged()) {
			if ($this->request->isGetRequest()) {
				return $this->response->setOutput($this->getVendorOrderProducts($vendorId));
			}
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}
	}

	//Get open order list of logged in vendor
	protected function getVendorOrders($vendorId) {

		if ($vendorId != $this->user->getVP()) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED));
		}

		$this->load->model('sale/vdi_order');

		$data = array();
		$data['filter_order_status'] = "1"; // Status code of orders with 'Pending' status
		$orders = $this->model_sale_vdi_order->getOrders($data);

		return $orders;
	}

	//Get the products for a specific order belonging to this vendor
	protected function getVendorOrderProducts($vendorId) {

		if ($vendorId != $this->user->getVP()) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED));
		}

		$this->load->model('sale/vdi_order');

		if (!array_key_exists('order_id', $this->request->get)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_BAD_PARAMETER));
		}

		$orderId = $this->request->get['order_id'];

		$orders = $this->model_sale_vdi_order->getOrderProducts($orderId);

		return $orders;
	}
}
