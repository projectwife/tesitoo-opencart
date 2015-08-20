<?php

class ControllerCartVoucherBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isPostRequest()) {
			$this->post();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	/**
	 * Resource methods
	 */

	public function post() {
		$data = parent::getInternalRouteData('checkout/voucher/voucher', true);
		ApiException::evaluateErrors($data);
	}
 
}

?>