<?php

class APIPaymentBase {

	private $customPaymentMethods = null;

	const IS_DIRECT_PAYMENT 			= 'is_direct_payment'; // Whether the payment method is a method for which customer needs to pay right now.
	const CONFIRMATION_ROUTE 			= 'confirmation_route'; // if IS_DIRECT_PAYMENT is false, which route needs to be called to confirm the order.
	const AUTO_CLICK_BUTTON				= 'auto_click_button'; // Whether the button on the page can be auto clicked when the page loaded, this is the case when there is only a submit button on the payment page without additional fields to fill in by the customer.

	/**
	 * Unsupported payment methods (based on default payment methods in OpenCart):
	 * - Amazon Payments (amazon_checkout)
	 * - PayPal Payflow Pro iFrame (pp_payflow_iframe)
	 */
	private static $paymentMethods = array(
		"authorizenet_aim" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"bank_transfer" => array(
			self::IS_DIRECT_PAYMENT => false,
			self::CONFIRMATION_ROUTE => "payment/bank_transfer/confirm",
			self::AUTO_CLICK_BUTTON => false
			),
		"cheque" => array(
			self::IS_DIRECT_PAYMENT => false,
			self::CONFIRMATION_ROUTE => "payment/cheque/confirm",
			self::AUTO_CLICK_BUTTON => false
			),
		"cod" => array(
			self::IS_DIRECT_PAYMENT => false,
			self::CONFIRMATION_ROUTE => "payment/cod/confirm",
			self::AUTO_CLICK_BUTTON => false
			),
		"free_checkout" => array(
			self::IS_DIRECT_PAYMENT => false,
			self::CONFIRMATION_ROUTE => "payment/free_checkout/confirm",
			self::AUTO_CLICK_BUTTON => false
			),
		"klarna_account" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"klarna_invoice" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"liqpay" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"moneybookers" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"nochex" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"paymate" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"paypoint" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"payza" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"perpetual_payments" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"pp_express" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"pp_pro" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"pp_pro_iframe" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"pp_pro_pf" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"pp_pro_uk" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"pp_standard" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"sagepay" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"sagepay_direct" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"sagepay_us" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"twocheckout" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"worldpay" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_amex" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_banktrans" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_dirdeb" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_directbank" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_giropay" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_ideal" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => false
			),
		"multisafepay_maestro" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_mastercard" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_mistercash" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_payafter" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_paypal" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_visa" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
		"multisafepay_wallet" => array(
			self::IS_DIRECT_PAYMENT => true,
			self::CONFIRMATION_ROUTE => null,
			self::AUTO_CLICK_BUTTON => true
			),
	);

	public function __construct() {

	}

	protected function setCustomPaymentMethods($customPaymentMethods) {
		$this->customPaymentMethods = $customPaymentMethods;
	}

	public function needsPaymentNow($paymentMethodCode) {
		$needsPaymentNow = $this->getPaymentProperty($paymentMethodCode, self::IS_DIRECT_PAYMENT, true);
		return $needsPaymentNow == null ? false : $needsPaymentNow;
	}

	public function getPaymentConfirmationRoute($paymentMethodCode) {
		$confirmationRoute = $this->getPaymentProperty($paymentMethodCode, self::CONFIRMATION_ROUTE, null);
		return $confirmationRoute;
	}

	public function isPaymentButtonAutoClick($paymentMethodCode) {
		$isPaymentButtonAutoClick = $this->getPaymentProperty($paymentMethodCode, self::AUTO_CLICK_BUTTON, false);
		return $isPaymentButtonAutoClick == null ? false : $isPaymentButtonAutoClick;
	}

	private function getPaymentProperty($paymentMethodCode, $property, $defaultValue) {
		$value = $defaultValue;

		if($this->customPaymentMethods != null && isset($this->customPaymentMethods[$paymentMethodCode])) {
			$paymentMethod = $this->customPaymentMethods[$paymentMethodCode];
			$value = $paymentMethod[$property];
		}
		else if(isset(self::$paymentMethods[$paymentMethodCode])) {
			$paymentMethod = self::$paymentMethods[$paymentMethodCode];
			$value = $paymentMethod[$property];
		}

		return $value;
	}
}

?>