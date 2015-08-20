<?php

class ControllerAccountOrderBaseAPI extends ApiController {

	public function index($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;

		if($this->request->isGetRequest()) {
			$this->get($id);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function reorder($args = array()) {
		if($this->request->isGetRequest()) {
			$this->getReorder(isset($args['id']) ? $args['id'] : null);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
	}

	public function redirect($url, $status = 302) {
		switch($url) {
			case 'account/login': // User not logged in
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN));
				break;

			case 'account/order/info':
				break;
		}
	}

	/**
	 * Resource methods
	 */
	
	public function get($id = NULL) {
		if($id != NULL) {
			$this->url->addCatalogLink('account/order/reorder');
			$this->request->get['order_id'] = $id;
			
			$data = parent::getInternalRouteData('account/order/info');

			if(isset($data['text_error'])) {
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_ORDER_NOT_FOUND, $data['text_error']);
			}

			$orderInfo = $this->model_account_order->getOrder($id);
			$order = array_merge($orderInfo, $data);

			$order = array('order' => $this->getOrder($order));
			$this->response->setOutput($order);
		}
		else {
			$data = parent::getInternalRouteData('account/order');

			$orders = array('orders' => $this->getOrders($data));
			$this->response->setOutput($orders);
		}

	}

	public function getReorder($id = NULL) {
		if($id != NULL) {
			$this->request->get['order_id'] = $id;

			$data = parent::getInternalRouteData('account/order/reorder');
		}
	}
	
	/**
	 * Helper methods
	 */

	protected function getOrders($data) {
		$orders = $data['orders'];

		return $this->processOrders($orders);
	}

	protected function processOrders($orders) {
		foreach($orders as &$order) {
			$order['order_id'] = (int)$order['order_id'];

			unset($order['href']);
		}

		return $orders;
	}

	protected function getOrder($data) {
		$order['order_id'] = (int)$data['order_id'];
		$order['date_added'] = $data['date_added'];
		$order['invoice_no'] = $data['invoice_no'];

		$order['firstname'] = $data['firstname'];
		$order['lastname'] = $data['lastname'];
		$order['telephone'] = $data['telephone'];
		$order['fax'] = $data['fax'];
		$order['email'] = $data['email'];

		$order['shipping_method'] = $data['shipping_method'];
		$order['shipping_firstname'] = $data['shipping_firstname'];
		$order['shipping_lastname'] = $data['shipping_lastname'];
		$order['shipping_company'] = $data['shipping_company'];
		$order['shipping_address_1'] = $data['shipping_address_1'];
		$order['shipping_address_2'] = $data['shipping_address_2'];
		$order['shipping_postcode'] = $data['shipping_postcode'];
		$order['shipping_city'] = $data['shipping_city'];
		$order['shipping_zone'] = $data['shipping_zone'];
		$order['shipping_country'] = $data['shipping_country'];
		$order['shipping_address_format'] = $data['shipping_address_format'];

		$order['payment_method'] = $data['payment_method'];
		$order['payment_firstname'] = $data['payment_firstname'];
		$order['payment_lastname'] = $data['payment_lastname'];
		$order['payment_company'] = $data['payment_company'];
		$order['payment_address_1'] = $data['payment_address_1'];
		$order['payment_address_2'] = $data['payment_address_2'];
		$order['payment_postcode'] = $data['payment_postcode'];
		$order['payment_city'] = $data['payment_city'];
		$order['payment_zone'] = $data['payment_zone'];
		$order['payment_country'] = $data['payment_country'];
		$order['payment_address_format'] = $data['payment_address_format'];

		$order['products'] = $data['products'];
		$order['totals'] = $data['totals'];
		$order['vouchers'] = $data['vouchers'];
		$order['histories'] = $data['histories'];
		$order['comment'] = $data['comment'];

		return $this->processOrder($order);
	}

	protected function processOrder($order) {
		$order['products'] = $this->processProducts($order['products']);
		$order['vouchers'] = $this->processVouchers($order['vouchers']);
		$order['totals'] = $this->processTotals($order['totals']);

		return $order;
	}

	protected function processProducts($products) {
		foreach($products as &$product) {
			$product['quantity'] = (int)$product['quantity'];

			$params = ApiUrl::getUrlParams($product['reorder']);
			$product['order_product_id'] = isset($params['order_product_id']) ? (int)$params['order_product_id'] : null;

			unset($product['reorder']);
			unset($product['return']);
		}

		return $products;
	}

	protected function processVouchers($vouchers) {
		return $vouchers;
	}

	protected function processTotals($totals) {
		return $totals;
	}
 
}

?>