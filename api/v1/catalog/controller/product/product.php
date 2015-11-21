<?php

require_once(DIR_API_APPLICATION . 'controller/product/base/product_base.php');

class ControllerProductProductAPI extends ControllerProductProductBaseAPI {

	private $defaultParameters = array(
		'sku' => '',
		'ean' => '',
		'jan' => '',
		'isbn' => '',
		'upc' => '',
		'mpn' => '',
		'location' => '',
		'quantity' => 0,
		'minimum' => 1,
		'subtract' => 1,
		'stock_status_id' => 6,
		'date_available' => '',
		'manufacturer_id' => '',
		'shipping' => '',
		'weight' => 0.0,
		'weight_class_id' => 1,
		'length' => 0.0,
		'width' => 0.0,
		'height' => 0.0,
		'length_class_id' => 1,
		'tax_class_id' => '',
		'sort_order' => 1,
		'status' => 5,    //5 for 'pending approval'
		'product_store' => [0],
		'product_category' => [],
		'product_description' => [
			1 => [   //1 for English
			'name' => '',
			'description' => '',
			'tag' => '',
			'meta_title' => '',
			'meta_description' => '',
			'meta_keyword' => ''
			]
		],
		'model' => ''

		//what are reqs for 'model'
		//meta_title is page title
	);

	public function index($args = array())
	{
		$id = isset($args['id']) ? $args['id'] : null;

		if($this->request->isGetRequest() && $id != null) {
			$this->get($id);
		}
		else if ($this->request->isPostRequest()) {
			if (null == $id) {
				$this->postNew();
			}
			else {
				$this->edit($id);
			}
		}
		else if ($this->request->isDeleteRequest() && $id != null) {
			$this->deleteProduct($id);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
	}

	public function image($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;

		if ($this->request->isPostRequest() && $id != null) {
			$this->uploadImage($id);
		}
		else if ($this->request->isDeleteRequest() && $id != null) {
			$this->deleteImage($id);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
	}

	public function postNew() {
		$json = array();

		$this->request->setDefaultParameters($this->defaultParameters);

		if ($this->user->isLogged()) {
			$this->request->post['vendor'] = $this->user->getVP();
		}

		//array index 1 means English
		$this->request->post['product_description'][1]['name'] = $this->request->post['name'];
		$this->request->post['product_description'][1]['description'] = $this->request->post['description'];
		$this->request->post['product_description'][1]['meta_title'] = $this->request->post['meta_title'];
		$this->request->post['price'] = (float)$this->request->post['price'];
		$this->request->post['quantity'] = (int)$this->request->post['quantity'];

		$category_ids = explode(",",$this->request->post['category_ids']);
		$this->request->post['product_category'] = $category_ids;

		if ('' === $this->request->post['model']) {
			$this->request->post['model'] = $this->request->post['name'];
		}

		$data = parent::getInternalRouteData('product/product/addNew', true);

		ApiException::evaluateErrors($data);

		$json['product_id'] = $data['product_id'];

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit($id) {
		if ($this->user->isLogged()) {
			$this->request->post['vendor'] = $this->user->getVP();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}

		//load product
		$this->load->model('catalog/vdi_product');
		$product = $this->model_catalog_vdi_product->getProduct((int)$id);

		//check if logged in vendor is owner of product with $id
		if ((!array_key_exists('vendor_id', $product)) ||
			($this->user->getVP() != (int)($product['vendor_id']))) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED));
		}

		//deal with fields from specified parameters (check validity)

		if (isset($this->request->post['price'])) {
			$product['price'] = (float)$this->request->post['price'];
		}
		if (isset($this->request->post['quantity'])) {
			$product['quantity'] = (int)$this->request->post['quantity'];
		}
		if (isset($this->request->post['minimum'])) {
			$product['minimum'] = (int)$this->request->post['minimum'];
		}
		if (isset($this->request->post['model'])) {
			$product['model'] = $this->request->post['model'];
		}

		//save product
		$this->model_catalog_vdi_product->editProductCoreDetails((int)$id, $product);

		//load product description
		$descriptions = $this->model_catalog_vdi_product->getProductDescriptions((int)$id);

		$description = $descriptions[1];

		if (($description['meta_title'] == $description['name'])
							&& (isset($this->request->post['name']))) {
			//update to same as name
			$description['meta_title'] = $this->request->post['name'];
		}
		if (isset($this->request->post['meta_title'])) {
			$description['meta_title'] = $this->request->post['meta_title'];
		}
		if (isset($this->request->post['name'])) {
			$description['name'] = $this->request->post['name'];
		}
		if (isset($this->request->post['description'])) {
			$description['description'] = $this->request->post['description'];
		}
		if (isset($this->request->post['tag'])) {
			$description['tag'] = $this->request->post['tag'];
		}
		if (isset($this->request->post['meta_description'])) {
			$description['meta_description'] = $this->request->post['meta_description'];
		}
		if (isset($this->request->post['meta_keyword'])) {
			$description['meta_keyword'] = $this->request->post['meta_keyword'];
		}

		//1 in the arguments means English
		$this->model_catalog_vdi_product->editProductDescription((int)$id, 1, $description);
	}

	public function deleteProduct($id = NULL) {
		if ($this->user->isLogged()) {
			$this->request->post['vendor'] = $this->user->getVP();
		}

		$productIds = explode(',', $id);

		foreach($productIds as $productId) {
			if (is_numeric($productId)) {
				$this->request->post['key'] = $productId;

				$data = parent::getInternalRouteData('product/product/delete', true);
				ApiException::evaluateErrors($data);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($data);
	}

	public function uploadImage($id) {
		if ($this->user->isLogged()) {
			$this->request->post['vendor'] = $this->user->getVP();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}

		$userName = $this->user->getUserName();
		$tmpFileName = $this->request->files['file']['tmp_name'];
		$srcFileName = urldecode($this->request->files['file']['name']);
		$tmpfilesize = filesize($this->request->files['file']['tmp_name']);

		//echo "size = " . $this->request->files['file']['size'] . "\n";

		$this->load->model('catalog/vdi_product');
		$product = $this->model_catalog_vdi_product->getProduct((int)$id);

		if ((!array_key_exists('vendor_id', $product)) ||
			($this->user->getVP() != (int)($product['vendor_id']))) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED));
		}

		//file name must not be null
		if (NULL == $srcFileName) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_FILE_ERROR, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_FILE_ERROR));
		}

		//file type must be acceptable
		$imageType = exif_imagetype($this->request->files['file']['tmp_name']);
		if ((IMAGETYPE_GIF != $imageType)
			&& (IMAGETYPE_JPEG != $imageType)
			&& (IMAGETYPE_PNG != $imageType)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_FILE_ERROR, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_FILE_ERROR));
		}

		//file size must be >0
		if (0 >= $tmpfilesize) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_FILE_ERROR, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_FILE_ERROR));
		}

		//append timestamp to all uploaded image filenames to ensure uniqueness
		$path_parts = pathinfo($srcFileName);
		$destination = "catalog/" . $userName . "/" . $path_parts['filename']
			. "_" . time() . "." . $path_parts['extension'];

		//$destination (eg. catalog/vendor1/Barley.jpg) is the string to put in the db

		//move tmpfile to proper location
		if (!rename($tmpFileName, DIR_IMAGE . $destination)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_FILE_ERROR, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_FILE_ERROR));
		}

		//default to using image as auxiliary product image
		$useImageAsMain = false;
		$sortOrder = 1;
		//unless specifically set to true
		if (isset($this->request->post['main_product_image']) &&
			('true' === $this->request->post['main_product_image'])) {
			$useImageAsMain = true;
		}
		else {
			if (isset($this->request->post['sort_order'])) {
				$sortOrder = $this->request->post['sort_order'];
			}
		}

		$this->load->model('catalog/vdi_product');
		if ($useImageAsMain) {
			$this->model_catalog_vdi_product
							->setMainProductImage($id, $destination);
		}
		else {
			$this->model_catalog_vdi_product
							->addAuxProductImage($id, $sortOrder, $destination);
		}
	}

	public function deleteImage($id) {
		if ($this->user->isLogged()) {
			$this->request->post['vendor'] = $this->user->getVP();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}

		if (!array_key_exists('files', $this->request->get)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_BAD_PARAMETER));
		}

		$imageFiles = explode(',', $this->request->get['files']);

		foreach($imageFiles as $imageFile) {
			$this->deleteSingleImage($id, urldecode($imageFile), $this->user->getUserName());
		}
	}

	public function deleteSingleImage($id, $imageFile, $userName) {

		//to make it easier for caller, we accept the 500x500 cached filename too
		$imageFile = preg_replace('/-500x500.jpg$/', '.jpg', $imageFile);

		//check this vendor owns specified product
		$this->load->model('catalog/vdi_product');
		$product = $this->model_catalog_vdi_product->getProduct((int)$id);

		if ((!array_key_exists('vendor_id', $product)) ||
			($this->user->getVP() != (int)($product['vendor_id']))) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED));
		}

		//if $imageFile is main image, then set property to default ("" / null ?)
		if (array_key_exists('image', $product)) {
			$mainImageBaseName = pathinfo($product['image'])['basename'];
			if ($mainImageBaseName === $imageFile) {
				$this->model_catalog_vdi_product
							->setMainProductImage($id, "");
			}
		}

		//remove any instance of $imageFile in aux images for this product
		$userFile = 'catalog/' . $userName . '/' . $imageFile;
		$this->model_catalog_vdi_product->removeAuxProductImage($id, $userFile);

		//check image is not used for any other product
		if (!$this->model_catalog_vdi_product->isImageInUse($userFile)) {

			//remove file from image catalog
			if (file_exists(DIR_IMAGE . $userFile)) {
				unlink(DIR_IMAGE . $userFile);
			}
			//don't clean up image cache - assume cleared periodically by routine maintenance
		}
	}

	//ADDED: tesitoo - david - 2015-08-25 - override to add vendor id & name
	protected function getProduct($id, $data) {

		$product = array();
		$product['product_id'] = $data['product_id'];
		$product['title'] = $data['heading_title'];
		$product['model'] = $data['model'];
		$product['description'] = $data['description'];
		$product['thumb_image'] = $data['thumb'];
		$product['image'] = $data['popup'];
		$product['images'] = $data['images'];
		$product['price'] = $data['price'];
		$product['tax'] = $data['tax'];
		$product['special'] = $data['special'];
		$product['discounts'] = $data['discounts'];
		$product['options'] = $data['options'];
		$product['manufacturer'] = $data['manufacturer'];
		$product['reward_points'] = (int)$data['reward'];
		$product['reward_points_needed_to_buy'] = (int)$data['points'];
		$product['attribute_groups'] = $data['attribute_groups'];
		$product['minimum_quantity'] = (int)$data['minimum'];
		$product['stock_status'] = $data['stock'];
		$product['related_products'] = $data['products'];
		$product['rating'] = $data['rating'];
		$product['reviews'] = $data['reviews'];
		$product['review_enabled'] = $data['review_status'] == 1 ? true : false;
		$product['recurrings'] = isset($data['recurrings']) ? $data['recurrings'] : null;
		$product['vendor_id'] = $data['vendor_id'];
		$product['vendor_name'] = $data['vendor_name'];

		return $this->processProduct($product);
	}

}

?>



