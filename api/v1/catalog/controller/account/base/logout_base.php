<?php

class ControllerAccountLogoutBaseAPI extends ApiController {
	
	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function redirect($url, $status = 302) {
		switch($url) {
			case 'account/logout': // Success
				$this->response->setHttpResponseCode(ApiResponse::HTTP_RESPONSE_CODE_OK);
				$this->response->output();
				exit();
				break;
		}
	}

	/**
	 * Resource methods
	 */

	public function get($id = NULL) {
		$data = parent::getInternalRouteData('account/logout');

		ApiException::evaluateErrors($data);
	}
 
}

?>