<?php

class ControllerAccountPasswordBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isPostRequest()) {
			$this->post();
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

			case 'account/account': // Success
				$this->response->setHttpResponseCode(ApiResponse::HTTP_RESPONSE_CODE_OK);
				$this->response->output();
				exit();
				break;
		}
	}

	/**
	 * Resource methods
	 */

	public function post() {
		$data = parent::getInternalRouteData('account/password');

		ApiException::evaluateErrors($data);
	}
 
}

?>