<?php

require_once(DIR_API_APPLICATION . 'controller/product/base/product_base.php');

class ControllerProductProductAPI extends ControllerProductProductBaseAPI {

	public function index($args = array()) {
		parent::index($args);
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



