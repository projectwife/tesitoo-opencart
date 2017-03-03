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

        if ($this->user->isLogged() && $this->user->getId()) {

            if (!$this->user->hasPermission('modify', 'user/vdi_user_password')) {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST,
                    ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED, "not allowed");
            }

            $this->load->model('user/vdi_user_password');
            $vdiUser = $this->model_user_vdi_user_password->getUser($this->user->getId());

            //either a password reset must have been recently requested
            if (isset($vdiUser['reset_pw_expiration'])) {
                if ((NULL == $vdiUser['reset_pw_expiration'] ||
                    strtotime($vdiUser['reset_pw_expiration']) < time()))
                {
                    throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED,
                        ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "unauthorised to reset password");
                }
            }
            //or we must login correctly with the old password
            else {
                if (!$user->login($this->user->getUserName(), $this->request->post['old_password'])) {
                    throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED,
                    ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "error confirming old password");
                }
            }

            //now set new password
            $this->model_user_vdi_user_password->editPassword($this->user->getId(),
                $this->request->post['new_password']);
            $data = array('success' => true );
        }
        else {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "user not logged in");
        }

        $this->response->setOutput($data);

        ApiException::evaluateErrors($data);
    }
}

?>