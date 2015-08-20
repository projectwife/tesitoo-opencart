<?php

class ControllerAccountRegisterBaseAPI extends ApiController {

	private $defaultParameters = array(
		'fax' => '',
		'company' => '',
		'address_2' => '',
		'agree' => 'true'
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
		switch($url) {
			case 'account/account': // Customer is already logged in
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_USER_ALREADY_LOGGED_IN, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_USER_ALREADY_LOGGED_IN));
				break;
 
			case 'account/success': // Success
				// Get account data
				$this->response->setInterceptOutput(false);
				$this->request->post = array();
				$this->request->server['REQUEST_METHOD'] = 'GET';
				$action = new ApiAction('account/account');
				$action->execute($this->registry);

				$this->response->setHttpResponseCode(ApiResponse::HTTP_RESPONSE_CODE_CREATED);
				$this->response->output();
				exit();
				break;
		}
	}

	/**
	 * Resource methods
	 */
	
	public function post() {
		$this->request->setDefaultParameters($this->defaultParameters);

		$this->request->convertBoolToCheckbox('agree');
		$this->request->convertBoolToYesNoRadioValue('newsletter');
			
		$data = parent::getInternalRouteData('account/register');

		ApiException::evaluateErrors($data);
	}
 
}

?>