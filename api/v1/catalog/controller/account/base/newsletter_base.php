<?php

class ControllerAccountNewsletterBaseAPI extends ApiController {
	
	public function index($args = array()) {
		if($this->request->isPutRequest()) {
			$this->put();
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

	public function put($id = NULL) {
		$this->request->convertBoolToYesNoRadioValue('newsletter');
			
		$this->request->server['REQUEST_METHOD'] = 'POST';
		$data = parent::getInternalRouteData('account/newsletter');

		ApiException::evaluateErrors($data);
	}
 
}

?>