<?php

class ControllerAccountLoginBaseAPI extends ApiController {
	
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
			case 'account/account': // Success
				// Get account data
				$this->response->setInterceptOutput(false);
				$this->request->server['REQUEST_METHOD'] = 'GET';
				$action = new ApiAction('account/account');
				$action->execute($this->registry);

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
		$data = parent::getInternalRouteData('account/login');

		ApiException::evaluateErrors($data);
	}
 
}

?>