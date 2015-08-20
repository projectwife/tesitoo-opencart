<?php

require_once(DIR_API_SYSTEM . 'library/payment_base.php');

/**
 * How to add your own payment methods:
 * - Add a new array with the payment method code as key in the $customPaymentMethods array below.
 * - Fill in the correct values for the payment method.
 * - More examples can be found in the APIPaymentBase class (payment_base.php in this directory) where the default payment methods are configured.
 */
class APIPayment extends APIPaymentBase {

	private static $customPaymentMethods = array(
		/**
		 * The payment method code of your payment method. If you don't know what the payment
		 * method code is you can look in your catalog/controller/payment folder and find
		 * the file of the payment method you want to add. The filename without .php is the
		 * payment method code.
		 */
		"payment_method_code" => array(
			/**
			 * If the payment method is a method for which customer needs to pay right now. Most
			 * of the time this value will be true, only for payment methods like Bank transfer,
			 * Cheque, Cash on Delivery or Free checkout it will be false.
			 */
			APIPaymentBase::IS_DIRECT_PAYMENT => true,

			/**
			 * if IS_DIRECT_PAYMENT is false, which route needs to be called internally to confirm 
			 * the order. If IS_DIRECT_PAYMENT is true you can just provide null as value.
			 */
			APIPaymentBase::CONFIRMATION_ROUTE => null,

			/**
			 * Whether the button on the page can be auto clicked when the page loaded, this is the 
			 * case when there is only a submit button on the payment page without additional fields 
			 * to fill in by the customer.
			 * The best way to determine if this boolean can be true is to look at the tpl file of 
			 * your payment method. These files can be found in view/theme/default/template/payment.
			 * Open your payment method template file and see if there are any non-hidden input or
			 * select fields the user needs to fill in. If that's the case this value needs to be
			 * false. If there are no input or select fields or they are all hidden you can use true.
			 */
			APIPaymentBase::AUTO_CLICK_BUTTON => true
			),
	);

	public function __construct() {
		parent::__construct();

		parent::setCustomPaymentMethods(self::$customPaymentMethods);
	}

}

?>