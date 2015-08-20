<?php

class ControllerOAuth2TokenApi extends ApiController {

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
		$oldAccessToken = isset($this->request->post['access_token']) ? $this->request->post['access_token'] : NULL;
		$accessToken = $this->oauth->generateAccessToken($oldAccessToken);
		$data = array();
		
		if($accessToken !== false) {
			$data['token_type'] = 'bearer';
			$data['access_token'] = $accessToken;
			$expiresIn = $this->config->has('api_access_token_ttl') ? (int)$this->config->get('api_access_token_ttl') : 0;
			$data['expires_in'] = $expiresIn;
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_INVALID_CLIENT, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_CLIENT));
		}
		

		$this->response->setOutput($data);
	}
}

?>