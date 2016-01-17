<?php


class ControllerVendorRegisterAPI extends ApiController {

	public function index($args = array()) {

		if ($this->request->isPostRequest()) {
			$this->handleRegistration();
		}
	}

	public function handleRegistration() {


	}
}
