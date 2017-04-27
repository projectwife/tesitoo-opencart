<?php


class ControllerNotificationDeviceAPI extends ApiController {

	public function index($args = array()) {

		//must be logged in to register device
        if ($this->user->isLogged()) {
            if($this->request->isPostRequest()) {
                $this->post();
            }
            else {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
            }
        }
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}
	}

	protected function registerDevice() {


    }
}

?>
