<?php

class ControllerProductManufacturerBaseAPI extends ApiController {

	private static $allowedOrder = array('ASC', 'DESC');
	private static $allowedSort = array('price' => 'p.price',
										'name' => 'pd.name',
										'rating' => 'rating',
										'model' => 'p.model',
										'sort_order' => 'p.sort_order',
										'quantity' => 'p.quantity',
										'date_added' => 'p.date_added');

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
		if($id != NULL) {
			$this->request->get['manufacturer_id'] = $id;
			$this->setRequestParams();

			$data = parent::getInternalRouteData('product/manufacturer/info');

			if(isset($data['text_error'])) {
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_MANUFACTURER_NOT_FOUND, $data['text_error']);
			}

			$manufacturer = array('manufacturer' => $this->getManufacturer($id, $data));
			$this->response->setOutput($manufacturer);
		}
		else {
			$manufacturers = array('manufacturers' => $this->getManufacturers());
			$this->response->setOutput($manufacturers);
		}

	}
	
	/**
	 * Helper methods
	 */
	
	protected function setRequestParams() {
		// sort
		if(isset($this->request->get['sort'])) {
			if(in_array($this->request->get['sort'], array_keys(ControllerProductManufacturerBaseAPI::$allowedSort))) {
				$this->request->get['sort'] = ControllerProductManufacturerBaseAPI::$allowedSort[$this->request->get['sort']];
			}
			else {
				$message = sprintf(ErrorCodes::getMessage(ErrorCodes::ERRORCODE_SORT_NOT_ALLOWED), implode(', ', array_keys(self::$allowedSort)));
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_SORT_NOT_ALLOWED, $message);
			}
		}

		// order
		if(isset($this->request->get['order'])) {
			if(!in_array($this->request->get['order'], ControllerProductManufacturerBaseAPI::$allowedOrder)) {
				$message = sprintf(ErrorCodes::getMessage(ErrorCodes::ERRORCODE_ORDER_NOT_ALLOWED), implode(', ', array_keys(self::$allowedOrder)));
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_ORDER_NOT_ALLOWED, $message);
			}
		}
	}

	protected function getManufacturers() {
		$this->load->model('catalog/manufacturer');
		$this->load->model('tool/image');

		$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

		foreach ($manufacturers as &$manufacturer) {
			$manufacturer['manufacturer_id'] = (int)$manufacturer['manufacturer_id'];

			unset($manufacturer['store_id']);
			unset($manufacturer['sort_order']);
			$manufacturer['thumb_image'] = $this->model_tool_image->resize($manufacturer['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			unset($manufacturer['image']);
		}

		return $manufacturers;
	}

	protected function getManufacturer($id, $data) {
		$manufacturer = array();
		$manufacturer['manufacturer_id'] = (int)$id;
		$manufacturer['name'] = $data['heading_title'];
		$manufacturer['products'] = $data['products'];

		return $this->processManufacturer($manufacturer);
	}

	protected function processManufacturer($manufacturer) {
		foreach($manufacturer['products'] as &$product) {
			$product = $this->processProduct($product);
		}

		return $manufacturer;
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