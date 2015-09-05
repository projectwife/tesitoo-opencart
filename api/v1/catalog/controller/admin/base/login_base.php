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
        $user = new User($this->registry);
       
        $data = array();

        if(isset($this->request->post['username']) && isset($this->request->post['username']) && $user->login($this->request->post['username'], $this->request->post['password'])) {
            $data['user'] = array('username' => $user->getUserName(), 'user_id' => $user->getId(), 'user_group_id' => $user->getGroupId());
        } else {
            // Need to keep the static messages in a separate file (Keep it in API end ?) 
            $data['errors'] = array('code' => "error_warning", 'message' => "Warning: No match for Username and/or Password.");    
        }
       
        $this->response->setOutput($data);

		ApiException::evaluateErrors($data);
	}
 
}

?>