<?php

class ControllerCheckoutGuestBaseAPI extends ApiController {

	private $defaultParameters = array(
		'fax' => '',
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
			case 'checkout/checkout': // Customer is logged in or guest checkout is not allowed.
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_USER_IS_LOGGED_IN_GUEST_CHECKOUT_NOT_ALLOWED, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_USER_IS_LOGGED_IN_GUEST_CHECKOUT_NOT_ALLOWED));
				break;
			
			case 'checkout/cart': // There are no products in the cart or there is no stock for 1 or more product(s).
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_NO_PRODUCTS_STOCK, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_NO_PRODUCTS_STOCK));
				break;
		}
	}

	/**
	 * Resource methods
	 */

	public function post() {
		$this->request->setDefaultParameters($this->defaultParameters);
		$this->request->convertBoolToCheckbox('shipping_address');

		$data = parent::getInternalRouteData('checkout/guest/save', true);

		if(isset($data['redirect'])) {
			$this->redirect($data['redirect']);
		}

		ApiException::evaluateErrors($data);
	}
 
}

?>