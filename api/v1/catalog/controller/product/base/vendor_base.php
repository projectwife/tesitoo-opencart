<?php

class ControllerProductVendorBaseAPI extends ApiController
{

	private static $allowedOrder = array('ASC', 'DESC');
	private static $allowedSort = array(
		'price'      => 'p.price',
		'name'       => 'pd.name',
		'rating'     => 'rating',
		'model'      => 'p.model',
		'sort_order' => 'p.sort_order',
		'quantity'   => 'p.quantity',
		'date_added' => 'p.date_added'
	);

	public function index($args = array())
	{
		$id = $this->getIdFromArgs($args);

		if ($this->request->isGetRequest()) {
			$this->get($id);
		} else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function get($id = null)
	{
		if ($id != null) {
			$vendor = array('vendor' => $this->getVendor($id));
			$this->response->setOutput($vendor);
		} else {
			$vendors = array('vendors' => $this->getVendors());
			$this->response->setOutput($vendors);
		}
	}

	protected function setRequestParams($id = null)
	{

		//vendor_id
		if(!$id !== null) {
			$this->request->get['vendor_id'] = $id;
		}
		// sort
		if (isset($this->request->get['sort'])) {
			if (in_array($this->request->get['sort'], array_keys(ControllerProductVendorBaseAPI::$allowedSort))) {
				$this->request->get['sort'] = ControllerProductVendorBaseAPI::$allowedSort[$this->request->get['sort']];
			} else {
				$message = sprintf(ErrorCodes::getMessage(ErrorCodes::ERRORCODE_SORT_NOT_ALLOWED), implode(', ', array_keys(self::$allowedSort)));
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_SORT_NOT_ALLOWED, $message);
			}
		}

		// order
		if (isset($this->request->get['order'])) {
			if (!in_array($this->request->get['order'], ControllerProductVendorBaseAPI::$allowedOrder)) {
				$message = sprintf(ErrorCodes::getMessage(ErrorCodes::ERRORCODE_ORDER_NOT_ALLOWED), implode(', ', array_keys(self::$allowedOrder)));
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_ORDER_NOT_ALLOWED, $message);
			}
		}
	}

	protected function getVendor($id)
	{
		$this->load->model('catalog/vendor');

		$vendor = $this->model_catalog_vendor->getVendor($id);

		return $this->processVendor($vendor);
	}

	protected function getVendors() {
		$this->load->model('catalog/vendor');

		$vendors_info = $this->model_catalog_vendor->getVendors();
		$vendors = array();

		foreach ($vendors_info as $data) {
			$vendors[] = $this->processVendor($data);
		}

		return $vendors;
	}

	protected function getVendorProducts($id)
	{
		$this->setRequestParams($id);

		$data = parent::getInternalRouteData('product/vendor/info');

		if (isset($data['text_error'])) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_VENDOR_NOT_FOUND, $data['text_error']);
		}

		$products = $data['products'];

		foreach($products as &$product) {
			$product = $this->processProduct($product);
		}

		return $products;
	}

	protected function processVendor($data) {

		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		$this->load->model('tool/image');

		$vendor = array(
			'vendor_id' => (int)$data['vendor_id'],
			'user_id' => (int)$data['user_id'],
			'username' => $data['username'],
			'name' => $data['vendor_name'],
			'thumb' => $this->model_tool_image->resize($data['vendor_image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
			'telephone' => $data['telephone'],
			'fax' => $data['fax'],
			'company' => $data['company'],
			'description' => $data['vendor_description'],
			'email' => $data['email'],
			'firstname' => $data['firstname'],
			'lastname' => $data['lastname'],
			'address' => array(
				'address_1' => $data['address_1'],
				'address_2' => $data['address_2'],
				'city' => $data['city'],
				'postcode' => $data['postcode'],
				'country' => $this->model_localisation_country->getCountry($data['country_id']),
				'zone' => $this->model_localisation_zone->getZone($data['zone_id']),
			),
			'accept_paypal' => $data['accept_paypal'],
			'accept_cheques' => $data['accept_cheques'],
			'accept_bank_transfer' => $data['accept_bank_transfer'],
			'sort_order' => $data['sort_order'],
		);

		if(isset($vendor['filtergroups'])) {
			$vendor['filtergroups'] = $this->processFilterGroups($vendor['filtergroups']);
		}

		return $vendor;
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
	
	protected function getIdFromArgs($args)
	{
	    return isset($args['id']) ? $args['id'] : null;
	}
}