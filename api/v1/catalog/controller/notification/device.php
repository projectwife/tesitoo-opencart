<?php


class ControllerNotificationDeviceAPI extends ApiController {

	public function index($args = array()) {

		//must be logged in to register device
        if ($this->user->isLogged()) {
            if($this->request->isPostRequest()) {
                return $this->response->setOutput($this->registerDevice());
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
        if (isset($this->request->post['firebase_device_registration_token'])) {

            $this->load->model('notification/device');

            $vendorId = $this->user->getVP();

            if (!preg_match("/^[0-9a-zA-Z\-\_:]*$/",
                $this->request->post['firebase_device_registration_token'])) {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, "registration token in unexpected format");
            }

            $this->model_notification_device->registerDevice($vendorId,
                $this->request->post['firebase_device_registration_token']);
        }
    }
}

?>
