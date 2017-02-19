<?php

class ControllerAdminLoginBaseAPI extends ApiController {
	
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

        if(isset($this->request->post['username']) && isset($this->request->post['password']) && $user->login($this->request->post['username'], $this->request->post['password'])) {
            $data['user'] = array('username' => $user->getUserName(), 'user_id' => $user->getId(), 'user_group_id' => $user->getGroupId(), 'vendor_id' => $user->getVP());
        } else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_NO_MATCH_USERAME_PASSWORD, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_NO_MATCH_USERAME_PASSWORD));
        }
       
        $this->response->setOutput($data);

		ApiException::evaluateErrors($data);
	}
}

?>