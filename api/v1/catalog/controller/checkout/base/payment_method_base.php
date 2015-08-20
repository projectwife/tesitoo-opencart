<?php

class ControllerCheckoutPaymentMethodBaseAPI extends ApiController {

	private $defaultParameters = array(
		'comment' => '',
		'agree' => 'true'
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
			case 'checkout/checkout': // No payment address is set
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_PAYMENT_ADDRESS_NOT_SET, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_PAYMENT_ADDRESS_NOT_SET));
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
		$data = parent::getInternalRouteData('checkout/payment_method');

		ApiException::evaluateErrors($data, false);
		
		$paymentMethods = array('payment_methods' => $this->getPaymentMethods($data));
		$this->response->setOutput($paymentMethods);
	}

	public function post() {
		$this->request->setDefaultParameters($this->defaultParameters);

		$this->request->convertBoolToCheckbox('agree');

		$data = parent::getInternalRouteData('checkout/payment_method/save', true);

		if(isset($data['redirect'])) {
			$this->redirect($data['redirect']);
		}
		
		ApiException::evaluateErrors($data);
	}

	/**
	 * Helper methods
	 */

 	protected function getPaymentMethods($data) {
		$paymentMethods = array_values($data['payment_methods']);

		return $this->processPaymentMethods($paymentMethods);
	}

	protected function processPaymentMethods($paymentMethods) {
		foreach($paymentMethods as &$paymentMethod) {
			unset($paymentMethod['sort_order']);
		}

		return $paymentMethods;
	}
}

?>