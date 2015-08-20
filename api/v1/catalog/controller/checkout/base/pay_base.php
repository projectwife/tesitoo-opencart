<?php

class ControllerCheckoutPayBaseAPI extends ApiController {

	public function __construct($registry) {
		parent::__construct($registry);

		$this->payment = new APIPayment();
	}

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	/**
	 * Resource methods
	 */

	public function get() {
		$paymentMethodCode = $this->session->data['payment_method']['code'];

		if($this->payment->needsPaymentNow($paymentMethodCode) === true) {
			$this->redirectToPaymentPage($paymentMethodCode);
		}
		else {
			$this->confirmPayment($paymentMethodCode);
		}
	}

	/**
	 * Helper methods
	 */
	
	protected function redirectToPaymentPage($paymentMethodCode) {
		$this->session->data['autosubmit'] = $this->payment->isPaymentButtonAutoClick($paymentMethodCode);

		// Load session into the "normal" file based session so it can be used when redirected to payment page.
		$this->session->writeToDefaultSession();

		$url = $this->url->catalogLink('checkout/apipay');

		$this->response->setRedirectCallback(null);
		$this->response->redirect($url);
	}

	protected function confirmPayment($paymentMethodCode) {
		// Do not intercept view data because the mail send to user when confirming the order
		// may contain html from templates which are loaded through the loader's view method.
		$this->load->setInterceptViewData(false);

		// Internally execute the confirmation route.
		$action = new Action($this->payment->getPaymentConfirmationRoute($paymentMethodCode));
		$action->execute($this->registry);
	}

}

?>