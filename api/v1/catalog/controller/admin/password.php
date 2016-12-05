<?php

require_once(DIR_API_APPLICATION . 'controller/admin/base/login_base.php');

class ControllerAdminPasswordAPI extends ApiController {

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
        $user = $this->user;

        $data = array();

        if ($this->user->isLogged()) {

            if (!$this->user->hasPermission('modify', 'user/vdi_user_password')) {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST,
                    ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED, "not allowed");
            }

            //log in to check old password
            if (!$user->login($this->user->getUserName(), $this->request->post['old_password'])) {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED,
                    ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "error confirming old password");
            }

            //now set new password
            if ($this->user->getId()) {
                $this->load->model('user/vdi_user_password');
                $this->model_user_vdi_user_password->editPassword($this->user->getId(),
                    $this->request->post['new_password']);
                $data = array('success' => true );
            }
        }
        else {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "user not logged in");
        }

        $this->response->setOutput($data);

        ApiException::evaluateErrors($data);
    }
}

?>