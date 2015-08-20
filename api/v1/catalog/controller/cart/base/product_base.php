<?php

require_once(DIR_API_APPLICATION . 'controller/cart/cart.php');

class ControllerCartProductBaseAPI extends ControllerCartCartAPI {

	public function index($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;

		if($this->request->isPostRequest()) {
			$this->post($id);
		}
		else if($this->request->isPutRequest()) {
			$this->put($id);
		}
		else if($this->request->isDeleteRequest()) {
			$this->delete($id);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function redirect($url, $status = 302) {
		parent::redirect($url, $status);
		
		switch($url) {
			case 'checkout/cart': // Success

				break;
		}
	}

	/**
	 * Resource methods
	 */

	public function post($id) {
		$data = parent::getInternalRouteData('checkout/cart/add', true);

		ApiException::evaluateErrors($data);

		// Return cart
		$this->request->post = array();
		$this->get();
	}

	public function put($id) {
		foreach($this->request->post as $productKey => $productQuantity) {
			$this->request->post['quantity'][$productKey] = $productQuantity;
		}

		if(!empty($this->request->post['quantity'])) {
			$data = parent::getInternalRouteData('checkout/cart/edit', true);
			ApiException::evaluateErrors($data);
		}

		// Return cart
		$this->request->post = array();
		$this->get();
	}

	public function delete($id) {
		$cartItemKeys = explode(',', $id);

		foreach($cartItemKeys as $cartItemKey) {
			$this->request->post['key'] = $cartItemKey;

			$data = parent::getInternalRouteData('checkout/cart/remove', true);
			ApiException::evaluateErrors($data);
		}
		
		// Return cart
		$this->request->post = array();
		$this->get();
	}
 
}

?>