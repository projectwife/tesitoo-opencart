<?php

class ControllerCheckoutConfirmBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function redirect($url, $status = 302) {
		switch ($url) {
			case 'checkout/checkout': // Order process not finished
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_ORDER_PROCESS_NOT_FINISHED, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_ORDER_PROCESS_NOT_FINISHED));
				break;
			
			case 'checkout/cart': // No products in cart, no stock for 1 or more product(s) or minimum quantity requirement of product not met
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_NO_PRODUCTS_STOCK_OR_MIN_QUANTITY, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_NO_PRODUCTS_STOCK_OR_MIN_QUANTITY));
				break;
		}
	}

	/**
	 * Resource methods
	 */

	public function get() {
		$data = parent::getInternalRouteData('checkout/confirm');

		if(isset($data['redirect'])) {
			$this->redirect($data['redirect']);
		}

		ApiException::evaluateErrors($data);

		$order = array('order' => $this->getOrder($data));
		$this->response->setOutput($order);
	}

	/**
	 * Helper methods
	 */

	protected function getOrder($data) {
		$order = array(
			'products' 	=> $data['products'],
			'vouchers' 	=> $data['vouchers'],
			'totals' 	=> $data['totals'],
		);

		$payment = new APIPayment();

		// Add info about the payment
		$paymentCode = $this->session->data['payment_method']['code'];
		$order['payment_information'] = $this->getAdditionalPaymentInfo($paymentCode);
		$order['needs_payment_now'] = $payment->needsPaymentNow($paymentCode);

		return $this->processOrder($order);
	}

	protected function processOrder($order) {
		$order['products'] = $this->processProducts($order['products']);
		$order['totals'] = $this->processTotals($order['totals']);
		$order['vouchers'] = $this->processVouchers($order['vouchers']);

		return $order;
	}

	protected function processProducts($products) {
		foreach($products as &$product) {
			$product['product_id'] = (int)$product['product_id'];

			unset($product['href']);
			unset($product['subtract']);
		}

		return $products;
	}

	protected function processVouchers($vouchers) {
		return $vouchers;
	}

	protected function processTotals($totals) {
		return $totals;
	}

	protected function getAdditionalPaymentInfo($paymentMethodCode) {
		$additionInfo = null;

		// Add additional information for some payment methods
		switch($paymentMethodCode) {
			case 'cheque':
				$action = new Action('payment/cheque');
				$data = $action->execute($this->registry);
				$additionInfo = $data['text_instruction'].'\n'.$data['text_payable'].'\n'.$data['payable'].'\n'.$data['text_address'].'\n'.$data['address'].'\n'.$data['text_payment'];
				break;
			case 'bank_transfer':
				$action = new Action('payment/bank_transfer');
				$data = $action->execute($this->registry);
				$additionInfo = $data['text_instruction'].'\n'.$data['text_description'].'\n'.$data['bank'].'\n'.$data['text_payment'];
				break;
		}

		return $additionInfo;
	}
 
}

?>