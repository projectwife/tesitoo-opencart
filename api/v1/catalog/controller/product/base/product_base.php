<?php

class ControllerProductProductBaseAPI extends ApiController {

	public function index($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;

		if($this->request->isGetRequest() && $id != null) {
			$this->get($id);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
	}

	public function review($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;

		if($this->request->isPostRequest() && $id != null) {
			$this->postReview($id);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
	}

	public function recurring_description($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;

		if($this->request->isGetRequest() && $id != null) {
			$this->getRecurringDescription($id);
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
	}

	/**
	 * Resource methods
	 */
	
	public function get($id = NULL) {
		$this->request->get['product_id'] = (int)$id;

		$data = parent::getInternalRouteData('product/product');

		if(isset($data['text_error'])) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_PRODUCT_NOT_FOUND, $data['text_error']);
		}

		$product = array('product' => $this->getProduct($id, $data));
		$this->response->setOutput($product);
	}

	public function postReview($id = NULL) {
		$this->request->get['product_id'] = $id;

		// Use dummy captcha to prevent error with captcha not matching.
		$this->session->data['captcha'] = 'dummycaptcha';
		$this->request->post['captcha'] = $this->session->data['captcha'];

		$data = parent::getInternalRouteData('product/product/write', true);

		ApiException::evaluateErrors($data);
	}

	public function getRecurringDescription($id = NULL) {
		$this->request->post['product_id'] = $id;
		$this->request->post['recurring_id'] = $this->request->get['recurring_id'];

		if(isset($this->request->get['quantity'])) {
			$this->request->post['quantity'] = $this->request->get['quantity'];
		}

		$data = parent::getInternalRouteData('product/product/getRecurringDescription', true);

		ApiException::evaluateErrors($data);

		if(isset($data['success'])) {
			$product = array('recurring_description' => $data['success']);
			$this->response->setOutput($product);
		}
		else {
			// No description found.
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_RECURRING_DESCRIPTION_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_RECURRING_DESCRIPTION_NOT_FOUND));
		}
	}
	
	/**
	 * Helper methods
	 */
	
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

		return $this->processProduct($product);
	}

	protected function processProduct($product) {
		$product['related_products'] = $this->processRelatedProducts($product['related_products']);
		$product['options'] = $this->processOptions($product['options']);
		$product['images'] = $this->processImages($product['images']);
		$product['attribute_groups'] = $this->processAttributes($product['attribute_groups']);
		$product['recurrings'] = $this->processRecurrings($product['recurrings']);
		$product['discounts'] = $this->processDiscounts($product['discounts']);

		if($product['price'] === false) {
			$product['price'] = null;
		}
		if($product['tax'] === false) {
			$product['tax'] = null;
		}
		if($product['special'] === false) {
			$product['special'] = null;
		}

		return $product;
	}

	protected function processRelatedProducts($relatedProducts) {
		foreach($relatedProducts as &$related_product) {
			$related_product['product_id'] = (int)$related_product['product_id'];

			// Filter out href
			unset($related_product['href']);

			$related_product['thumb_image'] = $related_product['thumb'];
			unset($related_product['thumb']);

			if($related_product['price'] === false) {
				$related_product['price'] = null;
			}
			if($related_product['special'] === false) {
				$related_product['special'] = null;
			}
		}

		return $relatedProducts;
	}

	protected function processOptions($options) {
		foreach($options as &$option) {
			$option['product_option_id'] = (int)$option['product_option_id'];
			$option['option_id'] = (int)$option['option_id'];
			$option['required'] = $option['required'] == 1 ? true : false;

			foreach($option['product_option_value'] as &$optionValue) {
				$optionValue['product_option_value_id'] = (int)$optionValue['product_option_value_id'];
				$optionValue['option_value_id'] = (int)$optionValue['option_value_id'];

				if($optionValue['price'] === false) {
					$optionValue['price'] = null;
				}
			}
		}

		return $options;
 	}

 	protected function processAttributes($attributeGroups) {
 		foreach($attributeGroups as &$attributeGroup) {
 			$attributeGroup['attribute_group_id'] = (int)$attributeGroup['attribute_group_id'];

 			foreach($attributeGroup['attribute'] as &$attribute) {
 				$attribute['attribute_id'] = (int)$attribute['attribute_id'];
 			}
 		}

 		return $attributeGroups;
 	}

 	protected function processImages($images) {
 		foreach($images as &$image) {
			$image['thumb_image'] = $image['thumb'];
			unset($image['thumb']);
			$image['image'] = $image['popup'];
			unset($image['popup']);
		}

		return $images;
 	}

 	protected function processRecurrings($recurrings) {
 		if(!empty($recurrings)) {
 			foreach($recurrings as &$recurring) {
	 			$recurring['recurring_id'] = (int)$recurring['recurring_id'];
	 			$recurring['language_id'] = (int)$recurring['language_id'];
	 		}
 		}

 		return $recurrings;
 	}

 	protected function processDiscounts($discounts) {
 		foreach($discounts as &$discount) {
 			$discount['quantity'] = (int)$discount['quantity'];
 		}

 		return $discounts;
 	}

}

?>