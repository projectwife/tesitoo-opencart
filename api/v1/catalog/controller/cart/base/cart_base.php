<?php

class ControllerCartCartBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function redirect($url, $status = 302) {
		switch($url) {
			case 'checkout/cart': // Success

				break;
		}
	}

	/**
	 * Resource methods
	 */

	public function get() {
		$data = parent::getInternalRouteData('checkout/cart');
		
		ApiException::evaluateErrors($data, false);

		$cart = array('cart' => $this->getCart($data));
		$this->response->setOutput($cart);
	}

	/**
	 * Helper methods
	 */

	protected function getCart($data) {
		$reward_points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$reward_points_total += $product['points'];
			}
		}	

		$cart = array(
			'products' 					=> isset($data['products']) ? $data['products'] : null,
			'vouchers' 					=> isset($data['vouchers']) ? $data['vouchers'] : null,
			'totals' 					=> isset($data['totals']) ? $data['totals'] : null,
			'weight' 					=> isset($data['weight']) ? $data['weight'] : null,
			'coupon_status' 			=> $this->config->get('coupon_status') == '1' ? true : false,
			'coupon' 					=> isset($data['coupon']['coupon']) && !empty($data['coupon']['coupon']) ? $data['coupon']['coupon'] : null,
			'voucher_status' 			=> $this->config->get('voucher_status') == '1' ? true : false,
			'voucher' 					=> isset($data['voucher']['voucher']) && !empty($data['voucher']['voucher']) ? $data['voucher']['voucher'] : null,
			'reward_status' 			=> $this->config->get('reward_status') == '1' ? true : false,
			'reward' 					=> isset($data['reward']['reward']) && !empty($data['reward']['reward']) ? $data['reward']['reward'] : 0,
			'max_reward_points_to_use' 	=> $reward_points_total,
			'shipping_status' 			=> $this->cart->hasShipping(),
			'error_warning'				=> !isset($data['error_warning']) || $data['error_warning'] == '' ? null : $data['error_warning']
			);

		return $this->processCart($cart);
	}

	protected function processCart($cart) {
		if(isset($cart['products'])) {
			$cart['products'] = $this->processProducts($cart['products']);
		}
		
		if(isset($cart['totals'])) {
			$cart['totals'] = $this->processTotals($cart['totals']);
		}
		
		if(isset($cart['vouchers'])) {
			$cart['vouchers'] = $this->processVouchers($cart['vouchers']);
		}

		return $cart;
	}

	protected function processProducts($products) {
		foreach ($products as &$product) {
			$product['thumb_image'] = $product['thumb'];
			$product['in_stock'] = $product['stock'];
			unset($product['stock']);
			unset($product['thumb']);
			unset($product['href']);
		}

		return $products;
	}

	protected function processVouchers($vouchers) {
		foreach($vouchers as &$voucher) {
			unset($voucher['remove']);
		}

		return $vouchers;
	}

	protected function processTotals($totals) {
		return $totals;
	}
 
}

?>