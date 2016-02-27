<?php
class ControllerCatalogVendorSetting extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('catalog/vendor_setting');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			if (isset($this->request->post['order_details'])) {
				$this->request->post['order_details'] = serialize($this->request->post['order_details']);
			}

			$this->model_setting_setting->editSetting('mvd', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('catalog/vendor_setting', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_new_order_message'] = $this->language->get('entry_new_order_message');
		$data['entry_history_order_message'] = $this->language->get('entry_history_order_message');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_checkout_order_status'] = $this->language->get('entry_checkout_order_status');
		$data['entry_history_order_status'] = $this->language->get('entry_history_order_status');
		$data['entry_order_status_availability'] = $this->language->get('entry_order_status_availability');
		$data['entry_multivendor_order_status'] = $this->language->get('entry_multivendor_order_status');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_payment_method'] = $this->language->get('entry_payment_method');
		$data['entry_cust_email'] = $this->language->get('entry_cust_email');
		$data['entry_cust_telephone'] = $this->language->get('entry_cust_telephone');
		$data['entry_shipping_address'] = $this->language->get('entry_shipping_address');
		$data['entry_vendor_address'] = $this->language->get('entry_vendor_address');
		$data['entry_vendor_email'] = $this->language->get('entry_vendor_email');
		$data['entry_vendor_telephone'] = $this->language->get('entry_vendor_telephone');
		$data['entry_status'] = $this->language->get('entry_status');
		
		$data['entry_desgin_tab'] = $this->language->get('entry_desgin_tab');
		$data['entry_reward_points'] = $this->language->get('entry_reward_points');
		$data['entry_menu_bar'] = $this->language->get('entry_menu_bar');
		$data['entry_vendor_product_approval'] = $this->language->get('entry_vendor_product_approval');
		$data['entry_vendor_product_edit_approval'] = $this->language->get('entry_vendor_product_edit_approval');
		$data['entry_bulk_products_activation'] = $this->language->get('entry_bulk_products_activation');

		$data['entry_vendor_tab'] = $this->language->get('entry_vendor_tab');
		$data['entry_category_menu'] = $this->language->get('entry_category_menu');
		$data['entry_product_limit'] = $this->language->get('entry_product_limit');
		$data['entry_paypal_sandbox'] = $this->language->get('entry_paypal_sandbox');
		$data['entry_show_plan'] = $this->language->get('entry_show_plan');
		$data['entry_product_notification'] = $this->language->get('entry_product_notification');
		
		$data['entry_order_detail'] = $this->language->get('entry_order_detail');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_order_history'] = $this->language->get('entry_order_history');
		$data['entry_vendor_invoice_address'] = $this->language->get('entry_vendor_invoice_address');
		$data['entry_customer_detail'] = $this->language->get('entry_customer_detail');
		$data['entry_order_history_update'] = $this->language->get('entry_order_history_update');
		$data['entry_allow_notification'] = $this->language->get('entry_allow_notification');
		$data['entry_multi_store_activated'] = $this->language->get('entry_multi_store_activated');
		$data['entry_multi_payment_gateway'] = $this->language->get('entry_multi_payment_gateway');
		$data['entry_bank_order_status'] = $this->language->get('entry_bank_order_status');
		$data['entry_bank_instruction'] = $this->language->get('entry_bank_instruction');
		$data['entry_signup_payment_method'] = $this->language->get('entry_signup_payment_method');
		$data['entry_stock_threshold'] = $this->language->get('entry_stock_threshold');
		
		//help
		$data['help_checkout_order_status'] = $this->language->get('help_checkout_order_status');
		$data['help_history_order_status'] = $this->language->get('help_history_order_status');
		$data['help_order_status_availability'] = $this->language->get('help_order_status_availability');
		$data['help_multivendor_order_status'] = $this->language->get('help_multivendor_order_status');
		$data['help_new_order_message'] = $this->language->get('help_new_order_message');
		$data['help_history_order_message'] = $this->language->get('help_history_order_message');
		$data['help_product_notification'] = $this->language->get('help_product_notification');
		$data['help_order_id'] = $this->language->get('help_order_id');
		$data['help_order_status'] = $this->language->get('help_order_status');
		$data['help_payment_method'] = $this->language->get('help_payment_method');
		$data['help_shipping_address'] = $this->language->get('help_shipping_address');
		$data['help_cust_email'] = $this->language->get('help_cust_email');
		$data['help_cust_telephone'] = $this->language->get('help_cust_telephone');
		$data['help_vendor_address'] = $this->language->get('help_vendor_address');
		$data['help_vendor_email'] = $this->language->get('help_vendor_email');
		$data['help_vendor_telephone'] = $this->language->get('help_vendor_telephone');
		
		$data['help_desgin_tab'] = $this->language->get('help_desgin_tab');
		$data['help_reward_points'] = $this->language->get('help_reward_points');
		$data['help_vendor_tab'] = $this->language->get('help_vendor_tab');
		$data['help_menu_bar'] = $this->language->get('help_menu_bar');
		$data['help_vendor_product_approval'] = $this->language->get('help_vendor_product_approval');
		$data['help_vendor_product_edit_approval'] = $this->language->get('help_vendor_product_edit_approval');
		$data['help_bulk_products_activation'] = $this->language->get('help_bulk_products_activation');

		$data['help_category_menu'] = $this->language->get('help_category_menu');
		$data['help_vendor_invoice_address'] = $this->language->get('help_vendor_invoice_address');
		$data['help_order_detail'] = $this->language->get('help_order_detail');
		$data['help_customer_detail'] = $this->language->get('help_customer_detail');
		$data['help_product'] = $this->language->get('help_product');
		$data['help_paypal'] = $this->language->get('help_paypal');
		
		$data['help_order_history'] = $this->language->get('help_order_history');
		$data['help_order_history_update'] = $this->language->get('help_order_history_update');
		$data['help_allow_notification'] = $this->language->get('help_allow_notification');
		$data['help_multi_store_activated'] = $this->language->get('help_multi_store_activated');
		$data['help_multi_payment_gateway'] = $this->language->get('help_multi_payment_gateway');
		$data['help_sign_up'] = $this->language->get('help_sign_up');
		$data['help_policy'] = $this->language->get('help_policy');
		$data['help_vendor_approval'] = $this->language->get('help_vendor_approval');
		$data['help_commission'] = $this->language->get('help_commission');
		$data['help_product_limit'] = $this->language->get('help_product_limit');		
		$data['help_category'] = $this->language->get('help_category');
		$data['help_store'] = $this->language->get('help_store');
		$data['help_signup_paypal_email'] = $this->language->get('help_signup_paypal_email');
		$data['help_show_plan'] = $this->language->get('help_show_plan');
		$data['help_stock_threshold'] = $this->language->get('help_stock_threshold');
		//help
		
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_order_status'] = $this->language->get('text_order_status');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_customer_contact'] = $this->language->get('text_customer_contact');
		$data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_vendor_address'] = $this->language->get('text_vendor_address');
		$data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$data['text_percentage'] = $this->language->get('text_percentage');
		$data['text_pf'] = $this->language->get('text_pf');
		$data['text_fp'] = $this->language->get('text_fp');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_edit'] = $this->language->get('text_edit');
		
		$data['entry_sign_up'] = $this->language->get('entry_sign_up');
		$data['entry_signup_paypal_email'] = $this->language->get('entry_signup_paypal_email');
		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['entry_policy'] = $this->language->get('entry_policy');
		$data['entry_vendor_approval'] = $this->language->get('entry_vendor_approval');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_products'] = $this->language->get('text_products');
		$data['text_new_order_message'] = $this->language->get('text_new_order_message');
		$data['text_history_order_message'] = $this->language->get('text_history_order_message');
		$data['text_paypal'] = $this->language->get('text_paypal');
		$data['text_bank'] = $this->language->get('text_bank');
				
		$data['tab_mail_setting'] = $this->language->get('tab_mail_setting');
		$data['tab_catalog'] = $this->language->get('tab_catalog');
		$data['tab_sales'] = $this->language->get('tab_sales');
		$data['tab_store'] = $this->language->get('tab_store');
		$data['tab_notification'] = $this->language->get('tab_notification');
		$data['tab_signup'] = $this->language->get('tab_signup');
		$data['tab_payment'] = $this->language->get('tab_payment');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$languages = $this->model_localisation_language->getLanguages();	
		$data['languages'] = $languages;

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		foreach ($languages as $language) {
			if (isset($this->error['new_order_message' . $language['language_id']])) {
				$data['error_code_new_message' . $language['language_id']] = $this->error['new_order_message' . $language['language_id']];
			} else {
				$data['error_code_new_message' . $language['language_id']] = '';
			}
		}
		
		foreach ($languages as $language) {
			if (isset($this->error['history_order_message' . $language['language_id']])) {
				$data['error_code_history_message' . $language['language_id']] = $this->error['history_order_message' . $language['language_id']];
			} else {
				$data['error_code_history_message' . $language['language_id']] = '';
			}
		}

		if (isset($this->error['code_message'])) {
			$data['error_code_message'] = $this->error['code_message'];
		} else {
			$data['error_code_message'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/vendor_setting', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['action'] = $this->url->link('catalog/vendor_setting', 'token=' . $this->session->data['token'], 'SSL');		
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['token'] = $this->session->data['token'];
		
		//tab-email-template
		if (isset($this->request->post['mvd_email_status'])) {
			$data['mvd_email_status'] = $this->request->post['mvd_email_status'];
		} else {
			$data['mvd_email_status'] = $this->config->get('mvd_email_status');
		}
		
		foreach ($languages as $language) {
			if (isset($this->request->post['mvd_new_order_message' . $language['language_id']])) {
				$data['mvd_new_order_message' . $language['language_id']] = $this->request->post['mvd_new_order_message' . $language['language_id']];
			} else {
				$data['mvd_new_order_message' . $language['language_id']] = $this->config->get('mvd_new_order_message' . $language['language_id']);
			}
		}
		
		foreach ($languages as $language) {
			if (isset($this->request->post['mvd_history_order_message' . $language['language_id']])) {
				$data['mvd_history_order_message' . $language['language_id']] = $this->request->post['mvd_history_order_message' . $language['language_id']];
			} else {
				$data['mvd_history_order_message' . $language['language_id']] = $this->config->get('mvd_history_order_message' . $language['language_id']);
			}
		}

		if (isset($this->request->post['mvd_show_order_id'])) {
			$data['mvd_show_order_id'] = $this->request->post['mvd_show_order_id'];
		} else {
			$data['mvd_show_order_id'] = $this->config->get('mvd_show_order_id');
		}
		
		if (isset($this->request->post['mvd_show_order_status'])) {
			$data['mvd_show_order_status'] = $this->request->post['mvd_show_order_status'];
		} else {
			$data['mvd_show_order_status'] = $this->config->get('mvd_show_order_status');
		}
		
		if (isset($this->request->post['mvd_show_payment_method'])) {
			$data['mvd_show_payment_method'] = $this->request->post['mvd_show_payment_method'];
		} else {
			$data['mvd_show_payment_method'] = $this->config->get('mvd_show_payment_method');
		}
		
		if (isset($this->request->post['mvd_show_cust_email'])) {
			$data['mvd_show_cust_email'] = $this->request->post['mvd_show_cust_email'];
		} else {
			$data['mvd_show_cust_email'] = $this->config->get('mvd_show_cust_email');
		}
		
		if (isset($this->request->post['mvd_show_cust_telephone'])) {
			$data['mvd_show_cust_telephone'] = $this->request->post['mvd_show_cust_telephone'];
		} else {
			$data['mvd_show_cust_telephone'] = $this->config->get('mvd_show_cust_telephone');
		}
		
		if (isset($this->request->post['mvd_show_cust_shipping_address'])) {
			$data['mvd_show_cust_shipping_address'] = $this->request->post['mvd_show_cust_shipping_address'];
		} else {
			$data['mvd_show_cust_shipping_address'] = $this->config->get('mvd_show_cust_shipping_address');
		}
		
		if (isset($this->request->post['mvd_show_vendor_address'])) {
			$data['mvd_show_vendor_address'] = $this->request->post['mvd_show_vendor_address'];
		} else {
			$data['mvd_show_vendor_address'] = $this->config->get('mvd_show_vendor_address');
		}
		
		if (isset($this->request->post['mvd_show_vendor_email'])) {
			$data['mvd_show_vendor_email'] = $this->request->post['mvd_show_vendor_email'];
		} else {
			$data['mvd_show_vendor_email'] = $this->config->get('mvd_show_vendor_email');
		}
		
		if (isset($this->request->post['mvd_show_vendor_telephone'])) {
			$data['mvd_show_vendor_telephone'] = $this->request->post['mvd_show_vendor_telephone'];
		} else {
			$data['mvd_show_vendor_telephone'] = $this->config->get('mvd_show_vendor_telephone');
		}
		
		//tab-catalog
		if (isset($this->request->post['mvd_product_approval'])) {
			$data['mvd_product_approval'] = $this->request->post['mvd_product_approval'];
		} else {
			$data['mvd_product_approval'] = $this->config->get('mvd_product_approval');
		}
		
		if (isset($this->request->post['mvd_product_edit_approval'])) {
			$data['mvd_product_edit_approval'] = $this->request->post['mvd_product_edit_approval'];
		} else {
			$data['mvd_product_edit_approval'] = $this->config->get('mvd_product_edit_approval');
		}
		
		if (isset($this->request->post['mvd_bulk_products_activation'])) {
			$data['mvd_bulk_products_activation'] = $this->request->post['mvd_bulk_products_activation'];
		} else {
			$data['mvd_bulk_products_activation'] = $this->config->get('mvd_bulk_products_activation');
		}
		
		if (isset($this->request->post['mvd_vendor_tab'])) {
			$data['mvd_vendor_tab'] = $this->request->post['mvd_vendor_tab'];
		} else {
			$data['mvd_vendor_tab'] = $this->config->get('mvd_vendor_tab');
		}
		
		if (isset($this->request->post['mvd_desgin_tab'])) {
			$data['mvd_desgin_tab'] = $this->request->post['mvd_desgin_tab'];
		} else {
			$data['mvd_desgin_tab'] = $this->config->get('mvd_desgin_tab');
		}
			
		if (isset($this->request->post['mvd_reward_points'])) {
			$data['mvd_reward_points'] = $this->request->post['mvd_reward_points'];
		} else {
			$data['mvd_reward_points'] = $this->config->get('mvd_reward_points');
		}
		
		if (isset($this->request->post['mvd_category_menu'])) {
			$data['mvd_category_menu'] = $this->request->post['mvd_category_menu'];
		} else {
			$data['mvd_category_menu'] = $this->config->get('mvd_category_menu');
		}
		
		if (isset($this->request->post['mvd_menu_bar'])) {
			$data['mvd_menu_bar'] = $this->request->post['mvd_menu_bar'];
		} else {
			$data['mvd_menu_bar'] = $this->config->get('mvd_menu_bar');
		}
		
		if (isset($this->request->post['mvd_sales_order_invoice_address'])) {
			$data['mvd_sales_order_invoice_address'] = $this->request->post['mvd_sales_order_invoice_address'];
		} else {
			$data['mvd_sales_order_invoice_address'] = $this->config->get('mvd_sales_order_invoice_address');
		}
		
		if (isset($this->request->post['mvd_sales_order_detail'])) {
			$data['mvd_sales_order_detail'] = $this->request->post['mvd_sales_order_detail'];
		} else {
			$data['mvd_sales_order_detail'] = $this->config->get('mvd_sales_order_detail');
		}
		
		if (isset($this->request->post['mvd_sales_customer_detail'])) {
			$data['mvd_sales_customer_detail'] = $this->request->post['mvd_sales_customer_detail'];
		} else {
			$data['mvd_sales_customer_detail'] = $this->config->get('mvd_sales_customer_detail');
		}
		
		if (isset($this->request->post['mvd_sales_product'])) {
			$data['mvd_sales_product'] = $this->request->post['mvd_sales_product'];
		} else {
			$data['mvd_sales_product'] = $this->config->get('mvd_sales_product');
		}
		
		if (isset($this->request->post['mvd_sales_order_history'])) {
			$data['mvd_sales_order_history'] = $this->request->post['mvd_sales_order_history'];
		} else {
			$data['mvd_sales_order_history'] = $this->config->get('mvd_sales_order_history');
		}
		
		if (isset($this->request->post['mvd_sales_order_history_update'])) {
			$data['mvd_sales_order_history_update'] = $this->request->post['mvd_sales_order_history_update'];
		} else {
			$data['mvd_sales_order_history_update'] = $this->config->get('mvd_sales_order_history_update');
		}
		
		if (isset($this->request->post['mvd_sales_order_allow_notification'])) {
			$data['mvd_sales_order_allow_notification'] = $this->request->post['mvd_sales_order_allow_notification'];
		} else {
			$data['mvd_sales_order_allow_notification'] = $this->config->get('mvd_sales_order_allow_notification');
		}
		
		if (isset($this->request->post['mvd_sales_order_status_availability'])) {
			$data['mvd_sales_order_status_availability'] = $this->request->post['mvd_sales_order_status_availability'];
		} else {
			$data['mvd_sales_order_status_availability'] = $this->config->get('mvd_sales_order_status_availability');
		}
		
		//tab-multi-store
		if (isset($this->request->post['mvd_store_activated'])) {
			$data['mvd_store_activated'] = $this->request->post['mvd_store_activated'];
		} else {
			$data['mvd_store_activated'] = $this->config->get('mvd_store_activated');
		}
		
		if (isset($this->request->post['mvd_payment_gateway_status'])) {
			$data['mvd_payment_gateway_status'] = $this->request->post['mvd_payment_gateway_status'];
		} else {
			$data['mvd_payment_gateway_status'] = $this->config->get('mvd_payment_gateway_status');
		}
		
		//tab-notification
		if (isset($this->request->post['mvd_product_notification'])) {
			$data['mvd_product_notification'] = $this->request->post['mvd_product_notification'];
		} else {
			$data['mvd_product_notification'] = $this->config->get('mvd_product_notification');
		}
		
		if (isset($this->request->post['mvd_stock_threshold'])) {
			$data['mvd_stock_threshold'] = $this->request->post['mvd_stock_threshold'];
		} else {
			$data['mvd_stock_threshold'] = $this->config->get('mvd_stock_threshold');
		}
		
		if (isset($this->request->post['mvd_order_status'])) {
			$data['mvd_order_status'] = $this->request->post['mvd_order_status'];
		} else {
			$data['mvd_order_status'] = $this->config->get('mvd_order_status');
		}
		
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['mvd_checkout_order_status'])) {
			$data['mvd_checkout_order_status'] = $this->request->post['mvd_checkout_order_status'];
		} elseif ($this->config->get('mvd_checkout_order_status')) {
			$data['mvd_checkout_order_status'] = $this->config->get('mvd_checkout_order_status');
		} else { 
			$data['mvd_checkout_order_status'] = array();
		}
		
		if (isset($this->request->post['mvd_history_order_status'])) {
			$data['mvd_history_order_status'] = $this->request->post['mvd_history_order_status'];
		} elseif ($this->config->get('mvd_history_order_status')) {
			$data['mvd_history_order_status'] = $this->config->get('mvd_history_order_status');
		} else { 
			$data['mvd_history_order_status'] = array();
		}
		
		//tab-signup
		if (isset($this->request->post['mvd_sign_up'])) {
			$data['mvd_sign_up'] = $this->request->post['mvd_sign_up'];
		} else {
			$data['mvd_sign_up'] = $this->config->get('mvd_sign_up');
		}
		
		if (isset($this->request->post['mvd_signup_auto_approval'])) {
			$data['mvd_signup_auto_approval'] = $this->request->post['mvd_signup_auto_approval'];
		} else {
			$data['mvd_signup_auto_approval'] = $this->config->get('mvd_signup_auto_approval');
		}
		
		if (isset($this->request->post['mvd_signup_show_plan'])) {
			$data['mvd_signup_show_plan'] = $this->request->post['mvd_signup_show_plan'];
		} else {
			$data['mvd_signup_show_plan'] = $this->config->get('mvd_signup_show_plan');
		}
		
		if (isset($this->request->post['mvd_signup_paypal_sandbox'])) {
			$data['mvd_signup_paypal_sandbox'] = $this->request->post['mvd_signup_paypal_sandbox'];
		} else {
			$data['mvd_signup_paypal_sandbox'] = $this->config->get('mvd_signup_paypal_sandbox');
		}
		
		if (isset($this->request->post['mvd_signup_paypal_email'])) {
			$data['mvd_signup_paypal_email'] = $this->request->post['mvd_signup_paypal_email'];
		} else {
			$data['mvd_signup_paypal_email'] = $this->config->get('mvd_signup_paypal_email');
		}
		
		$this->load->model('catalog/information');
		$data['informations'] = $this->model_catalog_information->getInformations();
		
		if (isset($this->request->post['mvd_signup_policy'])) {
			$data['mvd_signup_policy'] = $this->request->post['mvd_signup_policy'];
		} else {
			$data['mvd_signup_policy'] = $this->config->get('mvd_signup_policy');
		}
		
		$this->load->model('catalog/vendor');
		$data['signup_commissions'] = $this->model_catalog_vendor->getCommissionLimits();
		
		if (isset($this->request->post['mvd_signup_commission'])) {
			$data['mvd_signup_commission'] = $this->request->post['mvd_signup_commission'];
		} else {
			$getCommission_id = explode(':',$this->config->get('mvd_signup_commission'));
			$data['mvd_signup_commission'] = $getCommission_id[0];
		}
		
		$this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories(0);
		
		$data['mvd_signup_category'] = array();
		
		if (isset($this->request->post['mvd_signup_category'])) {
			$data['mvd_signup_category'] = $this->request->post['mvd_signup_category'];
		} else {
			$data['mvd_signup_category'] = $this->config->get('mvd_signup_category');
		}

		$this->load->model('setting/store');		
		$data['stores'] = $this->model_setting_store->getStores();
		
		$data['mvd_signup_store'] = array();
		
		if (isset($this->request->post['mvd_signup_store'])) {
			$data['mvd_signup_store'] = $this->request->post['mvd_signup_store'];
		} else {
			$data['mvd_signup_store'] = $this->config->get('mvd_signup_store');
		}

		foreach ($languages as $language) {
			if (isset($this->request->post['mvd_bank_transfer_bank' . $language['language_id']])) {
				$data['mvd_bank_transfer_bank' . $language['language_id']] = $this->request->post['mvd_bank_transfer_bank' . $language['language_id']];
			} else {
				$data['mvd_bank_transfer_bank' . $language['language_id']] = $this->config->get('mvd_bank_transfer_bank' . $language['language_id']);
			}
		}

		if (isset($this->request->post['mvd_bank_order_status'])) {
			$data['mvd_bank_order_status'] = $this->request->post['mvd_bank_order_status'];
		} else {
			$data['mvd_bank_order_status'] = $this->config->get('mvd_bank_order_status');
		}
		
		if (isset($this->request->post['mvd_paypal_status'])) {
			$data['mvd_paypal_status'] = $this->request->post['mvd_paypal_status'];
		} else {
			$data['mvd_paypal_status'] = $this->config->get('mvd_paypal_status');
		}
		
		if (isset($this->request->post['mvd_bank_status'])) {
			$data['mvd_bank_status'] = $this->request->post['mvd_bank_status'];
		} else {
			$data['mvd_bank_status'] = $this->config->get('mvd_bank_status');
		}
		
		if (isset($this->request->post['mvd_signup_default_payment_method'])) {
			$data['mvd_signup_default_payment_method'] = $this->request->post['mvd_signup_default_payment_method'];
		} else {
			$data['mvd_signup_default_payment_method'] = $this->config->get('mvd_signup_default_payment_method');
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/vendor_setting.tpl', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'catalog/vendor_setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (empty($this->request->post['mvd_new_order_message' . $language['language_id']])) {
				$this->error['new_order_message' .  $language['language_id']] = $this->language->get('error_code_new_message');
			}
		}
		
		foreach ($languages as $language) {
			if (empty($this->request->post['mvd_history_order_message' . $language['language_id']])) {
				$this->error['history_order_message' .  $language['language_id']] = $this->language->get('error_code_history_message');
			}
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>