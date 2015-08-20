<?php

class ControllerCheckoutPaymentAddressBaseAPI extends ApiController {

	private $defaultParameters = array(
		'company' => '',
		'address_2' => '',
		'postcode' => ''
	);

	public function index($args = array()) {
		if($this->request->isPostRequest()) {
			$this->post();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function redirect($url, $status = 302) {
		switch ($url) {
			case 'checkout/checkout': // Customer not logged in
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN));
				break;
			
			case 'checkout/cart': // No products in cart, no stock for 1 or more product(s) or minimum quantity requirement of product not met
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_NO_PRODUCTS_STOCK_OR_MIN_QUANTITY, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_NO_PRODUCTS_STOCK_OR_MIN_QUANTITY));
				break;
		}
	}

	/**
	 * Resource methods
	 */

	public function post() {
		$this->request->setDefaultParameters($this->defaultParameters);

		$data = parent::getInternalRouteData('checkout/payment_address/save', true);

		if(isset($data['redirect'])) {
			$this->redirect($data['redirect']);
		}
		
		ApiException::evaluateErrors($data);
	}
	
}

?>