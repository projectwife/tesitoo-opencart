<?php

require_once(DIR_API_APPLICATION . 'controller/product/base/vendor_base.php');

class ControllerProductVendorAPI extends ControllerProductVendorBaseAPI {

    public function index($args = array()) {
        parent::index($args);
    }
	
	public function products($args = array())
	{
	    return $this->response->setOutput($this->getVendorProducts($this->getIdFromArgs($args)));
	}
    
    public function orders() {
        if ($this->user->isLogged()) {
            return $this->response->setOutput($this->getVendorOrders());
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}
    }

}