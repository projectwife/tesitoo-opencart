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
}

?>
