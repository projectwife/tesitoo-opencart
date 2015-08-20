<?php

class ControllerCommonSettingsBaseAPI extends ApiController {

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
	
	public function get($id = NULL) {
		$settings = array('settings' => $this->getSettings());
		$this->response->setOutput($settings);
	}

	/**
	 * Helper methods
	 */
	
	protected function getSettings() {
		// Store
		$settings['store_title'] = $this->config->get('config_meta_title'); // Store title
		$settings['store_name'] = $this->config->get('config_name'); // Store name
		$settings['store_owner'] = $this->config->get('config_owner'); // Store owner
		$settings['store_address'] = $this->config->get('config_address'); // Store address
		$settings['store_email'] = $this->config->get('config_email'); // Store email
		$settings['store_telephone'] = $this->config->get('config_telephone'); // Store telephone
		$settings['store_fax'] = $this->config->get('config_fax'); // Store fax

		// Store logo
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$settings['store_logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
		} else {
			$settings['store_logo'] = null;
		}

		$settings['display_product_count'] = $this->config->get('config_product_count') == '1' ? true : false; // Category Product Count: Show the number of products inside the subcategories in the storefront header category menu. Be warned, this will cause an extreme performance hit for stores with a lot of subcategories!
		$settings['default_customer_group_id'] = (int)$this->config->get('config_customer_group_id'); // Customer Group: Default customer group.

		// Account Terms: Forces people to agree to terms before an account can be created.
		if($this->config->get('config_account_id') == 0) {
			$settings['account_terms'] = null;
		}
		else {
			$settings['account_terms'] = $this->url->catalogLink('information/information', 'information_id=' . $this->config->get('config_account_id'), 'SSL');
		}

		$settings['guest_checkout_allowed'] = $this->config->get('config_guest_checkout') == '1' ? true : false; // Guest Checkout: Allow customers to checkout without creating an account. This will not be available when a downloadable product is in the shopping cart.

		// Checkout Terms: Forces people to agree to terms before an a customer can checkout.
		if($this->config->get('config_checkout_id') == 0) {
			$settings['checkout_terms'] = null;
		}
		else {
			$settings['checkout_terms'] = $this->url->catalogLink('information/information', 'information_id=' . $this->config->get('config_checkout_id'), 'SSL');
		}

		$settings['no_stock_checkout'] = $this->config->get('config_stock_checkout') == '1' ? true : false; // Stock Checkout: Allow customers to still checkout if the products they are ordering are not in stock.

		return $settings;
	}
}

?>