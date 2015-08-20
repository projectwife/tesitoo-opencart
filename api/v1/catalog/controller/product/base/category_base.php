<?php

class ControllerProductCategoryBaseAPI extends ApiController {

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

	public function subcategory($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;
		
		if($this->request->isGetRequest()) {
			$this->getSubcategories($id);
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
			$this->request->get['path'] = $id;
			$this->setRequestParams();

			$data = parent::getInternalRouteData('product/category');

			if(isset($data['text_error'])) {
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_CATEGORY_NOT_FOUND, $data['text_error']);
			}

			$category = array('category' => $this->getCategory($id, $data));
			$this->response->setOutput($category);
		}
		else {
			$categories = array('categories' => $this->getCategories());
			$this->response->setOutput($categories);
		}
	}

	public function getSubcategories($id = NULL) {
		// If no id is set return the root categories.
		$id = $id == NULL ? 0 : $id;

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$categories = array('categories' => $this->getCategoryById($id, false));
		$this->response->setOutput($categories);
	}

	/**
	 * Helper methods
	 */
	
	protected function setRequestParams() {
		// sort
		if(isset($this->request->get['sort'])) {
			if(in_array($this->request->get['sort'], array_keys(ControllerProductCategoryBaseAPI::$allowedSort))) {
				$this->request->get['sort'] = ControllerProductCategoryBaseAPI::$allowedSort[$this->request->get['sort']];
			}
			else {
				$message = sprintf(ErrorCodes::getMessage(ErrorCodes::ERRORCODE_SORT_NOT_ALLOWED), implode(', ', array_keys(self::$allowedSort)));
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_SORT_NOT_ALLOWED, $message);
			}
		}

		// order
		if(isset($this->request->get['order'])) {
			if(!in_array($this->request->get['order'], ControllerProductCategoryBaseAPI::$allowedOrder)) {
				$message = sprintf(ErrorCodes::getMessage(ErrorCodes::ERRORCODE_ORDER_NOT_ALLOWED), implode(', ', array_keys(self::$allowedOrder)));
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_ORDER_NOT_ALLOWED, $message);
			}
		}
	}

	protected function getCategories() {
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$categories = $this->getCategoryById(0);

		return $categories;
	}

	protected function getCategoryById($categoryId, $recursive = true) {
		$categories = $this->model_catalog_category->getCategories($categoryId);

		foreach ($categories as &$category) {
			// Filter elements
			$filteredCategory = array();
			$filteredCategory['category_id'] = (int)$category['category_id'];
			$filteredCategory['name'] = $category['name'];
			$filteredCategory['description'] = $category['description'];
			$filteredCategory['thumb_image'] = $this->model_tool_image->resize($category['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));

			$data = array(
				'filter_category_id'  => $category['category_id'],
				'filter_sub_category' => true);

			$product_total = $this->model_catalog_product->getTotalProducts($data);
			$filteredCategory['total_products'] = (int)$product_total;

			if($recursive == true) {
				$filteredCategory['categories'] = $this->getCategoryById($category['category_id']);
			}
			else {
				$filteredCategory['subcategory_count'] = (int)$this->model_catalog_category->getTotalCategoriesByCategoryId($category['category_id']);
			}

			$category = $filteredCategory;
		}

		return $categories;
	}

	protected function getCategory($id, $data) {
		$category = array();
		$category['category_id'] = (int)$id;
		$category['name'] = $data['heading_title'];
		$category['description'] = $data['description'];
		$category['thumb_image'] = $data['thumb'];
		$category['products'] = $data['products'];

		$filterGroups = null;
		if(method_exists($this->model_catalog_category, 'getCategoryFilters')) {
			$this->load->model('catalog/category');
			$filterGroups = $this->model_catalog_category->getCategoryFilters($id);
		}
		$category['filtergroups'] = $filterGroups;

		return $this->processCategory($category);
	}

	protected function processCategory($category) {
		foreach($category['products'] as &$product) {
			$product = $this->processProduct($product);
		}

		if(isset($category['filtergroups'])) {
			$category['filtergroups'] = $this->processFilterGroups($category['filtergroups']);
		}

		return $category;
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

	protected function processFilterGroups($filterGroups) {
		foreach($filterGroups as &$filterGroup) {
			$filterGroup['filter_group_id'] = (int)$filterGroup['filter_group_id'];

			foreach($filterGroup['filter'] as &$filter) {
				$filter['filter_id'] = (int)$filter['filter_id'];
			}
		}

		return $filterGroups;
	}
}

?>