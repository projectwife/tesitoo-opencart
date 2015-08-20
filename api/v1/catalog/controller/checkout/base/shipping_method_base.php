<?php

class ControllerCheckoutShippingMethodBaseAPI extends ApiController {

	private $defaultParameters = array(
		'comment' => ''
	);

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		elseif($this->request->isPostRequest()) {
			$this->post();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function redirect($url, $status = 302) {
		switch ($url) {
			case 'checkout/checkout': // No shipping address is set or shipping isn't required
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_SHIPPING_ADDRESS_NOT_SET_OR_SHIPPING_NOT_NEEDED, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_SHIPPING_ADDRESS_NOT_SET_OR_SHIPPING_NOT_NEEDED));
				break;
			
			case 'checkout/cart': // No products in cart, no stock for 1 or more product(s) or minimum quantity requirement of product not met
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_NO_PRODUCTS_STOCK_OR_MIN_QUANTITY, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_NO_PRODUCTS_STOCK_OR_MIN_QUANTITY));
				break;
		}
	}

	/**
	 * Resource methods
	 */
	
	public function get() {
		$data = parent::getInternalRouteData('checkout/shipping_method');

		ApiException::evaluateErrors($data, false);
		
		$shippingMethods = array('shipping_methods' => $this->getShippingMethods($data));
		$this->response->setOutput($shippingMethods);
	}

	public function post() {
		$this->request->setDefaultParameters($this->defaultParameters);

		$data = parent::getInternalRouteData('checkout/shipping_method/save', true);

		if(isset($data['redirect'])) {
			$this->redirect($data['redirect']);
		}
		
		ApiException::evaluateErrors($data);
	}

	/**
	 * Helper methods
	 */

 	protected function getShippingMethods($data) {
		$shippingMethods = array_values($data['shipping_methods']);

		return $this->processShippingMethods($shippingMethods);
	}

	protected function processShippingMethods($shippingMethods) {
		foreach($shippingMethods as &$shippingMethod) {
			if($shippingMethod['error'] === false || empty($shippingMethod['error'])) {
				$shippingMethod['error'] = null;
			}

			$shippingMethod['quote'] = array_values($shippingMethod['quote']);
			$shippingMethod['quote'] = $this->processQuotes($shippingMethod['quote']);
			unset($shippingMethod['sort_order']);
		}

		return $shippingMethods;
	}

	protected function processQuotes($quotes) {
		foreach($quotes as &$quote) {
			$quote['cost'] = (double)$quote['cost'];
			$quote['display_cost'] = $quote['text'];
			unset($quote['text']);
			unset($quote['tax_class_id']);
		}
		
		return $quotes;
	}
}

?>