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
		'expiration_date' => '',
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
			'meta_title' => '', //meta_title is page title
			'meta_description' => '',
			'meta_keyword' => ''
			]
		],
		'model' => '' //FIXME what are reqs for 'model'
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

	//override to allow retrieval of pending products
    public function get($id = NULL) {
		$this->request->get['product_id'] = (int)$id;
		$this->request->get['include_pending'] = 1;

		$data = parent::getInternalRouteData('product/product_tesitoo');

		if(isset($data['text_error'])) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_PRODUCT_NOT_FOUND, $data['text_error']);
		}

		$product = array('product' => $this->getProduct($id, $data));
		$this->response->setOutput($product);
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

	protected function convertDateToMySQLDateTime($datetime) {
        //convert dates if possible, if not return null
        $expDateOut = null;
        //date formats tested in order - if the date matches one, use it
        $validDateFormats = [DateTime::ISO8601, 'Y-m-d H:i:s', 'Y-m-d'];
        foreach ($validDateFormats as $fmt) {
            $expDateIn = DateTime::createFromFormat($fmt, $datetime);
            if ($expDateIn) {
                //then we've found a valid date format, output it in MySQL DateTime format
                $expDateOut = $expDateIn->format('Y-m-d H:i:s');
                break;
            }
        }
        return $expDateOut;
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
		$this->request->post['price'] = (string)$this->request->post['price'];
		$this->request->post['quantity'] = (int)$this->request->post['quantity'];

		$category_ids = explode(",",$this->request->post['category_ids']);
		$this->request->post['product_category'] = $category_ids;

		if ('' === $this->request->post['model']) {
			$this->request->post['model'] = $this->request->post['name'];
		}

		if ('' === $this->request->post['shipping']) {
            $this->request->post['shipping'] = '1';
        }

        $this->request->post['expiration_date'] = $this->convertDateToMySQLDateTime($this->request->post['expiration_date']);

		$data = parent::getInternalRouteData('product/product/addNew', true);

		ApiException::evaluateErrors($data);

        if ($this->config->get('mvd_product_notification')) {
				$this->add_edit_notification(true, $this->request->post['name']);
				$this->add_edit_vendor_notification(true, $this->request->post['name']);
        }

		$json['product_id'] = $data['product_id'];

		//$this->response->addHeader('Content-Type: application/json');
		//$this->response->setOutput(json_encode($json));
		$this->response->setOutput($json);
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
			$product['price'] = (string)$this->request->post['price'];
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
		if (isset($this->request->post['stock_status_id'])) {
			$product['stock_status_id'] = (int)$this->request->post['stock_status_id'];
		}
		if (isset($this->request->post['weight_class_id'])) {
			$product['weight_class_id'] = (int)$this->request->post['weight_class_id'];
		}
		if (isset($this->request->post['weight'])) {
			$product['weight'] = (float)$this->request->post['weight'];
		}
		if (isset($this->request->post['length_class_id'])) {
			$product['length_class_id'] = (int)$this->request->post['length_class_id'];
		}
		if (isset($this->request->post['length'])) {
			$product['length'] = (float)$this->request->post['length'];
		}
		if (isset($this->request->post['width'])) {
			$product['width'] = (float)$this->request->post['width'];
		}
		if (isset($this->request->post['height'])) {
			$product['height'] = (float)$this->request->post['height'];
		}

        if (isset($this->request->post['expiration_date'])) {
            $product['expiration_date'] = $this->convertDateToMySQLDateTime($this->request->post['expiration_date']);
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

		if (isset($this->request->post['category_ids'])) {
			$catData = array();
			$inputCatIds = explode(",",$this->request->post['category_ids']);
			$catData['product_category'] = array_filter(array_unique($inputCatIds), "is_numeric");
			$this->model_catalog_vdi_product->editProductCategories((int)$id, $catData);
		}

        if ($this->config->get('mvd_product_notification')) {
				$this->add_edit_notification(false, $description['name']);
				$this->add_edit_vendor_notification(false, $description['name']);
        }
	}

	public function deleteProduct($id = NULL) {
		if ($this->user->isLogged()) {
			$this->request->post['vendor'] = $this->user->getVP();
		}

		$this->load->model('catalog/vdi_product');
		$product = $this->model_catalog_vdi_product->getProduct((int)$id);
		if ((!array_key_exists('vendor_id', $product)) ||
			($this->user->getVP() != (int)($product['vendor_id']))) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_VENDOR_NOT_ALLOWED));
		}

		if (!array_key_exists('product_id', $product)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND,
			ErrorCodes::ERRORCODE_PRODUCT_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_PRODUCT_NOT_FOUND));
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
		$json = array();

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
		$fileNameTimestamped = $path_parts['filename']
			. "_" . time() . "." . $path_parts['extension'];
		$destination = "catalog/" . $userName . "/" . $fileNameTimestamped;

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

		$json['filename'] = $fileNameTimestamped;

		$this->response->setOutput($json);
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

		//to make it easier for caller, we accept NNNxNNN (eg. 500x500) cached filenames too
		$imageFile = preg_replace('/-[0-9]{2,3}x[0-9]{2,3}.jpg$/', '.jpg', $imageFile);

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
	//ADDED: tesitoo - david - 2016-10-13 - add quantity and status
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
		$product['currency_code'] = $data['currency_code'];
		$product['display_price'] = $data['display_price'];
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
		$product['quantity'] = $data['quantity'];
		$product['status'] = $data['status'];
		$product['location'] = $data['location'];
		$product['length_class_id'] = $data['length_class_id'];
		$product['weight_class_id'] = $data['weight_class_id'];
		$product['categories'] = $data['categories'];
		$product['date_added'] = $data['date_added'];
		$product['expiration_date'] = $data['expiration_date'];

		return $this->processProduct($product);
	}


    public function add_edit_notification($pmode = true,$pname) {

		$this->language->load('mail/email_notification');

		$this->load->model('catalog/vdi_product');

		$vendor_data = $this->model_catalog_vdi_product->getVendorName($this->user->getId());

		if ($pmode) {
			$subject = sprintf($this->language->get('text_subject_add'), $pname, $vendor_data['vendor_name']);
		} else {
			$subject = sprintf($this->language->get('text_subject_edit'), $vendor_data['vendor_name'], $pname);
		}

		$text = sprintf($this->language->get('text_to'), $this->config->get('config_owner')) . "<br><br>";

		if ($pmode) {
			$text .= sprintf($this->language->get('text_message_add'), $pname, $vendor_data['vendor_name']) . "<br><br>";
		} else {
			$text .= sprintf($this->language->get('text_message_edit'), $pname, $vendor_data['vendor_name']) . "<br><br>";
		}

		$text .= $this->language->get('text_thanks') . "<br>";
		$text .= $this->config->get('config_name') . "<br><br>";
		$text .= $this->language->get('text_system');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
		$mail->send();
    }

    public function add_edit_vendor_notification($pmode = true,$pname) {

		$this->language->load('mail/email_notification');

		$this->load->model('catalog/vdi_product');

		$vendor_data = $this->model_catalog_vdi_product->getVendorName($this->user->getId());

		if ($pmode) {
			$subject = sprintf($this->language->get('text_subject_confirm_add'), $pname);
		} else {
			$subject = sprintf($this->language->get('text_subject_confirm_edit'), $pname);
		}

		$text = sprintf($this->language->get('text_to'), $vendor_data['firstname'] . ' ' . $vendor_data['lastname']) . "<br><br>";

		if ($pmode) {
			$text .= sprintf($this->language->get('text_message_confirm_add'), $pname) . "<br><br>";
		} else {
			$text .= sprintf($this->language->get('text_message_confirm_edit'), $pname) . "<br><br>";
		}

        $text .= $this->language->get('text_thanks') . "<br>";
		$text .= $this->config->get('config_name') . "<br><br>";
		$text .= $this->language->get('text_system');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($vendor_data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($text, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	}
}

?>



