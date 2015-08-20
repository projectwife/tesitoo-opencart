<?php

require_once(DIR_APPLICATION . 'controller/account/wishlist.php');

class ControllerAccountWishlistBaseAPI extends ApiController {

	public function index($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;

		if($this->request->isGetRequest()) {
			$this->get($id);
		}
		else if($this->request->isPostRequest()) {
			$this->post($id);
		}
		else if($this->request->isDeleteRequest()) {
			$this->delete($id);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}
	
	public function redirect($url, $status = 302) {
		switch($url) {
			case 'account/login': // User not logged in
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN));
				break;

			case 'account/wishlist': // Success delete
				$this->response->setInterceptOutput(false);
				$this->request->get = array();
				$this->get();

				$this->response->setHttpResponseCode(ApiResponse::HTTP_RESPONSE_CODE_OK);
				$this->response->output();
				exit();
				break;
		}
	}

	/**
	 * Resource methods
	 */
	
	public function get($id = NULL) {
		$data = parent::getInternalRouteData('account/wishlist');

		$wishlist = array('wishlist' => $this->getWishlist($data));
		$this->response->setOutput($wishlist);
	}

	public function post($id) {
		if($id !== NULL) {
			$this->request->post['product_id'] = $id;
		}

		$data = parent::getInternalRouteData('account/wishlist/add', true);

		ApiException::evaluateErrors($data);

		$this->request->post = array();
		$this->get();
	}

	public function delete($id = NULL) {
		if($id !== NULL) {
			$this->request->get['remove'] = $id;
		}

		$data = parent::getInternalRouteData('account/wishlist');

		ApiException::evaluateErrors($data);
	}
	
	/**
	 * Helper methods
	 */

	protected function getWishList($data) {
		$wishlist['products'] = $data['products'];

		return $this->processWishList($wishlist);
	}

	protected function processWishList($wishlist) {
		$wishlist['products'] = $this->processProducts($wishlist['products']);

		return $wishlist;
	}

	protected function processProducts($products) {
		foreach($products as &$product) {
			$product['product_id'] = (int)$product['product_id'];
			$product['thumb_image'] = $product['thumb'];
			unset($product['thumb']);
			unset($product['href']);
			unset($product['remove']);

			if($product['price'] === false) {
				$product['price'] = null;
			}
			if($product['special'] === false) {
				$product['special'] = null;
			}
		}

		return $products;
	}
 
}

?>