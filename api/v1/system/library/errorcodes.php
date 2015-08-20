<?php

class ErrorCodes {
	// oauth
	const ERRORCODE_INVALID_CLIENT 					= 'invalid_client';
	const ERRORCODE_INVALID_ACCESS_TOKEN			= 'invalid_access_token';

	// product
	const ERRORCODE_METHOD_NOT_FOUND 				= 'method_not_found';
	const ERRORCODE_SORT_NOT_ALLOWED 				= 'sort_not_allowed';
	const ERRORCODE_ORDER_NOT_ALLOWED 				= 'order_not_allowed';
	const ERRORCODE_CATEGORY_NOT_FOUND 				= 'category_not_found';
	const ERRORCODE_MANUFACTURER_NOT_FOUND 			= 'manufacturer_not_found';
	const ERRORCODE_PRODUCT_NOT_FOUND 				= 'product_not_found';
	const ERRORCODE_RECURRING_DESCRIPTION_NOT_FOUND = 'recurring_description_not_found';

	// account
	const ERRORCODE_USER_NOT_LOGGED_IN 				= 'user_not_logged_in';
	const ERRORCODE_USER_ALREADY_LOGGED_IN 			= 'user_already_logged_in';
	const ERRORCODE_ADDRESS_NOT_FOUND 				= 'address_not_found';
	const ERRORCODE_ORDER_NOT_FOUND 				= 'order_not_found';

	// common
	const ERRORCODE_COUNTRY_NOT_FOUND 				= 'country_not_found';
	const ERRORCODE_CUSTOM_FIELD_LOCATION_INVALID 	= 'custom_field_location_invalid';

	// checkout
	const ERRORCODE_NO_PRODUCTS_STOCK_OR_MIN_QUANTITY 					= 'no_products_stock_or_min_quantity';
	const ERRORCODE_NO_PRODUCTS_STOCK 									= 'no_products_stock';
	const ERRORCODE_SHIPPING_NOT_REQUIRED 								= 'shipping_not_required';
	const ERRORCODE_PAYMENT_ADDRESS_NOT_SET 							= 'payment_address_not_set';
	const ERRORCODE_SHIPPING_ADDRESS_NOT_SET_OR_SHIPPING_NOT_NEEDED		= 'shipping_address_not_set_or_shipping_not_needed';
	const ERRORCODE_ORDER_PROCESS_NOT_FINISHED 							= 'order_process_not_finished';
	const ERRORCODE_USER_IS_LOGGED_IN_GUEST_CHECKOUT_NOT_ALLOWED 		= 'user_is_logged_in_guest_checkout_not_allowed';

	private static $errorMessages = array(
		self::ERRORCODE_INVALID_CLIENT 									=> 'Client authentication failed (e.g., unknown client, no client authentication included, or unsupported authentication method).',
		self::ERRORCODE_INVALID_ACCESS_TOKEN 							=> 'Invalid or expired access token',
		self::ERRORCODE_USER_ALREADY_LOGGED_IN							=> 'The user is already logged in.',
		self::ERRORCODE_USER_NOT_LOGGED_IN 								=> 'User is not logged in.',
		self::ERRORCODE_METHOD_NOT_FOUND 								=> 'API method not found.',
		self::ERRORCODE_SORT_NOT_ALLOWED 								=> 'Allowed sort values are %s',
		self::ERRORCODE_ORDER_NOT_ALLOWED 								=> 'Allowed order values are %s',
		self::ERRORCODE_ADDRESS_NOT_FOUND 								=> 'Address could not be found.',
		self::ERRORCODE_COUNTRY_NOT_FOUND 								=> 'Country could not be found.',
		self::ERRORCODE_NO_PRODUCTS_STOCK_OR_MIN_QUANTITY 				=> 'There are no products in the cart, no stock for 1 or more product(s) or the minimum quantity requirement of a product is not met.',
		self::ERRORCODE_NO_PRODUCTS_STOCK 								=> 'There are no products in the cart or there is no stock for 1 or more product(s).',
		self::ERRORCODE_SHIPPING_NOT_REQUIRED 							=> 'Shipping is not required for the products in the cart.',
		self::ERRORCODE_PAYMENT_ADDRESS_NOT_SET 						=> 'The payment address is not set.',
		self::ERRORCODE_SHIPPING_ADDRESS_NOT_SET_OR_SHIPPING_NOT_NEEDED => 'The shipping address is not set or shipping is not required for the products in the cart.',
		self::ERRORCODE_ORDER_PROCESS_NOT_FINISHED 						=> 'A step in the order process was skipped so we are unable to show the order overview.',
		self::ERRORCODE_USER_IS_LOGGED_IN_GUEST_CHECKOUT_NOT_ALLOWED 	=> 'User is logged in or guest checkout is not allowed.',
		self::ERRORCODE_PRODUCT_NOT_FOUND 								=> 'The product could not be found.',
		self::ERRORCODE_CUSTOM_FIELD_LOCATION_INVALID 					=> 'The given custom field location is invalid.',
		self::ERRORCODE_RECURRING_DESCRIPTION_NOT_FOUND 				=> 'The recurring description could not be found.'
	);

	public static function getMessage($errorCode) {
		return self::$errorMessages[$errorCode];
	}
}

?>