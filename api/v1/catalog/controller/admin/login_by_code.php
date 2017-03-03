<?php

class ControllerAdminLoginByCodeAPI extends ApiController {

    public function index($args = array()) {
        if($this->request->isPostRequest()) {
            $this->post();
        }
        else {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
        }
    }

    public function post() {
        $user = $this->user;
        $data = array();

        $this->load->model('user/user');

        if(isset($this->request->post['code']) && $user->loginByCode($this->request->post['code'])) {
            $data['user'] = array('username' => $user->getUserName(), 'user_id' => $user->getId(), 'user_group_id' => $user->getGroupId(), 'vendor_id' => $user->getVP());
        }
        else {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_INVALID_ACCESS_TOKEN, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_ACCESS_TOKEN));
        }

        $this->response->setOutput($data);

        ApiException::evaluateErrors($data);
    }
}

?>