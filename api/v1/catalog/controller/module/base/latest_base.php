<?php

class ControllerModuleLatestBaseAPI extends ApiController {

	const DEFAULT_LIMIT = 5;
	const DEFAULT_IMAGE_WIDTH = 80;
	const DEFAULT_IMAGE_HEIGHT = 80;

	public function index($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;

		if($this->request->isGetRequest()) {
			$this->get($id);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
		
	}

	/**
	 * Resource methods
	 */
	
	public function get($id = NULL) {
		$settings = array();
		$settings['limit'] = isset($this->request->get['limit']) ? $this->request->get['limit'] : self::DEFAULT_LIMIT;
		$settings['width'] = isset($this->request->get['image_width']) ? $this->request->get['image_width'] : self::DEFAULT_IMAGE_WIDTH;
		$settings['height'] = isset($this->request->get['image_height']) ? $this->request->get['image_height'] : self::DEFAULT_IMAGE_HEIGHT;

		$action = new Action('module/latest', $settings);
		$data = $action->execute($this->registry);

		$latestProducts = array('products' => $this->getLatestProducts($data));
		$this->response->setOutput($latestProducts);
	}

	/**
	 * Helper methods
	 */
	
	protected function getLatestProducts($data) {
		foreach($data['products'] as &$product) {
			unset($product['href']);
			$product['product_id'] = (int)$product['product_id'];
			$product['thumb_image'] = $product['thumb'];
			unset($product['thumb']);

			if($product['price'] === false) {
				$product['price'] = null;
			}
			if($product['special'] === false) {
				$product['special'] = null;
			}

			$product['rating'] = $product['rating'] === false ? 0 : $product['rating']; // Bug in opencart returns false when rating is disabled.
		}

		return $data['products'];
	}

}

?>