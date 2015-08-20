<?php

class ControllerCheckoutSuccessBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function redirect($url, $status = 302) {
	}

	/**
	 * Resource methods
	 */

	public function get() {
		parent::getInternalRouteData('checkout/success');

		unset($this->session->data['autosubmit']);
	}
 
}

?>