<?php

class ControllerOAuth2InvalidTokenApi extends ApiController {

	public function index($args = array()) {
		throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_INVALID_ACCESS_TOKEN, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_ACCESS_TOKEN));
	}
	
}

?>