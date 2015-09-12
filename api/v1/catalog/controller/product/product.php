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
			$this->postNew($id);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
	}

	public function postNew($id = NULL)
	{
		$json = array();

		$this->request->setDefaultParameters($this->defaultParameters);

		if ($this->user->isLogged())
		{
            $this->request->post['vendor'] = $this->user->getVP();
        }

		$this->request->post['product_description'][1]['name'] = $this->request->post['name'];
		$this->request->post['product_description'][1]['description'] = $this->request->post['description'];
		$this->request->post['product_description'][1]['meta_title'] = $this->request->post['meta_title'];
		$this->request->post['price'] = (float)$this->request->post['price'];
		$this->request->post['quantity'] = (int)$this->request->post['quantity'];
		if ('' === $this->request->post['model'])
		{
            $this->request->post['model'] = $this->request->post['name'];
        }

		$data = parent::getInternalRouteData('product/product/addNew', true);

		ApiException::evaluateErrors($data);

		$json['product_id'] = $data['product_id'];

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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



