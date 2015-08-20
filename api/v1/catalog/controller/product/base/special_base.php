<?php

class ControllerProductSpecialBaseAPI extends ApiController {

	private static $allowedOrder = array('ASC', 'DESC');
	private static $allowedSort = array('price' => 'p.price',
										'name' => 'pd.name',
										'rating' => 'rating',
										'model' => 'p.model',
										'sort_order' => 'p.sort_order');
	
	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
	}

	/**
	 * Resource methods
	 */
	
	public function get($id = NULL) {
		$this->setRequestParams();
			
		$data = parent::getInternalRouteData('product/special');

		$products = array('products' => $this->getProducts($data));
		$this->response->setOutput($products);
	}
	
	/**
	 * Helper methods
	 */
	
	protected function setRequestParams() {
		// sort
		if(isset($this->request->get['sort'])) {
			if(in_array($this->request->get['sort'], array_keys(ControllerProductSpecialBaseAPI::$allowedSort))) {
				$this->request->get['sort'] = ControllerProductSpecialBaseAPI::$allowedSort[$this->request->get['sort']];
			}
			else {
				$message = sprintf(ErrorCodes::getMessage(ErrorCodes::ERRORCODE_SORT_NOT_ALLOWED), implode(', ', array_keys(self::$allowedSort)));
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_SORT_NOT_ALLOWED, $message);
			}
		}

		// order
		if(isset($this->request->get['order'])) {
			if(!in_array($this->request->get['order'], ControllerProductSpecialBaseAPI::$allowedOrder)) {
				$message = sprintf(ErrorCodes::getMessage(ErrorCodes::ERRORCODE_ORDER_NOT_ALLOWED), implode(', ', array_keys(self::$allowedOrder)));
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_ORDER_NOT_ALLOWED, $message);
			}
		}
	}

	protected function getProducts($data) {
		$products = $data['products'];

		return $this->processProducts($products);
	}

	protected function processProducts($products) {
		foreach($products as &$product) {
			$product = $this->processProduct($product);
		}

		return $products;
	}

	protected function processProduct($product) {
		$product['product_id'] = (int)$product['product_id'];

		// Remove href
		unset($product['href']);
		if($product['price'] === false) {
			$product['price'] = null;
		}
		if($product['tax'] === false) {
			$product['tax'] = null;
		}
		if($product['special'] === false) {
			$product['special'] = null;
		}
		$product['thumb_image'] = $product['thumb'];
		unset($product['thumb']);

		return $product;
	}
 
}

?>