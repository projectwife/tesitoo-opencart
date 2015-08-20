<?php
class ControllerReportMVDVendorTransaction extends Controller { 
	public function index() {  
		$this->load->language('report/mvd_vendor_transaction');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('report/mvd_vendor_transaction');
	
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
		
		if (isset($this->request->get['filter_vendor_group'])) {
			$filter_vendor_group = $this->request->get['filter_vendor_group'];
		} else {
			$filter_vendor_group = 0;
		}
		
		if (isset($this->request->get['filter_paid_status'])) {
			$filter_paid_status = $this->request->get['filter_paid_status'];
		} else {
			$filter_paid_status = 0;
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}	
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_vendor_group'])) {
			$url .= '&filter_vendor_group=' . $this->request->get['filter_vendor_group'];
		}
		
		if (isset($this->request->get['filter_paid_status'])) {
			$url .= '&filter_paid_status=' . $this->request->get['filter_paid_status'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
								
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/mvd_vendor_transaction', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
			
		$this->load->model('report/mvd_vendor_transaction');
		
		$data['orders'] = array();
		$data['histories'] = array();
		
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_vendor_group'    => $filter_vendor_group,
			'filter_paid_status'     => $filter_paid_status,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);
		
		$order_total = $this->model_report_mvd_vendor_transaction->getVendorTotalOrders($filter_data);
		$results = $this->model_report_mvd_vendor_transaction->getVendorOrders($filter_data);
		$store_revenues = $this->model_report_mvd_vendor_transaction->getVendorTotalAmount($filter_data);
		$data['vendors_name'] = $this->model_report_mvd_vendor_transaction->getVendorsName($filter_data);
		
		$payment_histories = $this->model_report_mvd_vendor_transaction->getPaymentHistory(0);
		
		$shipping_charges = $this->model_report_mvd_vendor_transaction->getShippingChargedTotal($filter_data);
		
		$shipping_charged = 0;
		foreach ($shipping_charges AS $shipping_charge) {
			$shipping_charged = $shipping_charge['shipping_charged'];
			$shipping_tax = $shipping_charge['shipping_tax'];
		}
		
		$discount_coupons = $this->model_report_mvd_vendor_transaction->getCouponTotal($filter_data);
		$coupon_amount = 0;
		foreach ($discount_coupons AS $discount_coupon) {
			$coupon_amount = $discount_coupon['discount_amount'];
			$coupon_tax = $discount_coupon['discount_tax'];
		}
		
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
	
		$data['commission_data'] = $this->model_report_mvd_vendor_transaction->getCommissionData();
		
		foreach ($payment_histories as $payment_history) {
			$data['histories'][] = array (
				'payment_id'	=> $payment_history['payment_id'],
				'name'			=> $payment_history['name'],
				'details'		=> unserialize(trim($payment_history['details'])),
				'amount'		=> $this->currency->format($payment_history['payment_amount'], $this->config->get('config_currency')),
				'payment_type'	=> $payment_history['payment_type'],
				'date'			=> date($this->language->get('date_format_short2'), strtotime($payment_history['payment_date']))				
				);
		}
		
		foreach ($results as $result) {
			$data['orders'][] = array(
				'product_id'	=> $result['product_id'],
				'date' 			=> date($this->language->get('date_format_short2'), strtotime($result['date'])),
				'order_id'  	=> $result['order_id'],
				'order_product_id'	=> $result['order_product_id'],
				'order_status'  => $result['order_status'],
				'product_name'  => $result['product_name'] . '<br/><b>(' . $this->model_report_mvd_vendor_transaction->getVendorName($result['vendor_id']) . ')</b>',
				'pp_product_name'  => $result['product_name'],
				'price'         => $this->currency->format($result['price'] + ($this->config->get('tax_status') ? $result['tax']: 0), $this->config->get('config_currency')),
				'quantity'  	=> $result['quantity'],
				'vendor_id'		=> $result['vendor_id'],
				'title'			=> $result['title'],
				'commission'  	=> $this->currency->format($result['commission'] + ($this->config->get('tax_status') ? $result['store_tax']: 0), $this->config->get('config_currency')),
				'total'			=> $this->currency->format($result['total'] + ($this->config->get('tax_status') ? $result['total_tax']: 0), $this->config->get('config_currency')),
				'amount'  		=> $this->currency->format($result['amount'] + ($this->config->get('tax_status') ? $result['vendor_tax']: 0),$this->config->get('config_currency')),
				'paypal_amount'	=> $this->currency->format($result['amount'] + ($this->config->get('tax_status') ? $result['vendor_tax']: 0),$this->config->get('config_currency'), false, false),
				'paypal_email'	=> $this->model_report_mvd_vendor_transaction->getVendorPaypalEmail($result['vendor_id']),
				'tax'			=> $result['total_tax'],
				'store_tax'		=> $result['store_tax'],
				'vendor_tax'	=> $result['vendor_tax'],
				'paid_status'   => $result['paid_status']
			);
		}

		foreach ($store_revenues AS $store) {
			$data['store_revenue'][] = array (
				'vendor_id'			=> $store['vendor_id'],
				'paypal_email'		=> $store['paypal_email'],
				'company'			=> $store['company'],
				'paid_amount'		=> $store['vendor_amount'],
				'shipping_charged'	=> $this->currency->format($shipping_charged + ($this->config->get('tax_status') ? $shipping_tax: 0), $this->config->get('config_currency')),
				'coupon_amount'		=> $this->currency->format($coupon_amount + ($this->config->get('tax_status') ? $coupon_tax: 0), $this->config->get('config_currency')),
				'vendor_amount'  	=> $this->currency->format($store['vendor_amount'] + ($this->config->get('tax_status') ? $store['total_vendor_tax']: 0), $this->config->get('config_currency')),
				'amount_pay_vendor' => $this->currency->format($store['vendor_amount'] + $shipping_charged - $coupon_amount + ($this->config->get('tax_status') ? ($store['total_vendor_tax'] + $shipping_tax - $coupon_tax): 0), $this->config->get('config_currency')),
				'paypal_amount' 	=> $this->currency->format($store['vendor_amount'] + $shipping_charged - $coupon_amount + ($this->config->get('tax_status') ? ($store['total_vendor_tax'] + $shipping_tax - $coupon_tax): 0), $this->config->get('config_currency'), false, false),
				'commission'  		=> $this->currency->format($store['commission'] + ($this->config->get('tax_status') ? $store['total_store_tax']: 0), $this->config->get('config_currency')),
				'revenue_shipping' 	=> $this->currency->format($store['gross_amount'] + $shipping_charged + ($this->config->get('tax_status') ? ($store['total_tax'] + $shipping_tax): 0), $this->config->get('config_currency')),
				'gross_amount'  	=> $this->currency->format($store['gross_amount'] + ($this->config->get('tax_status') ? $store['total_tax']: 0), $this->config->get('config_currency'))
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_all_status'] = $this->language->get('text_all_status');
		$data['text_all_vendors'] = $this->language->get('text_all_vendors');
		$data['text_gross_incomes'] = $this->language->get('text_gross_incomes');
		$data['text_commission'] = $this->language->get('text_commission');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_coupon'] = $this->language->get('text_coupon');
		$data['text_vendor_earning'] = $this->language->get('text_vendor_earning');
		$data['text_vendor_revenue'] = $this->language->get('text_vendor_revenue');
		$data['text_amount_pay_vendor'] = $this->language->get('text_amount_pay_vendor');
		$data['text_payment_history'] = $this->language->get('text_payment_history');
		$data['text_vendor_payment_history'] = $this->language->get('text_vendor_payment_history');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_subscription'] = $this->language->get('text_subscription');
		$data['title_payment_type'] = $this->language->get('title_payment_type');
		$data['help_gross_revenue'] = $this->language->get('help_gross_revenue');
		
		$data['help_store_revenue'] = $this->language->get('help_store_revenue');
		$data['help_store_commission'] = $this->language->get('help_store_commission');
		$data['help_vendor_balance'] = $this->language->get('help_vendor_balance');
		$data['help_total_shipping'] = $this->language->get('help_total_shipping');
		$data['help_total_coupon'] = $this->language->get('help_total_coupon');
		$data['help_amount_to_vendor'] = $this->language->get('help_amount_to_vendor');
		$data['help_pay_now'] = $this->language->get('help_pay_now');
		
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_order_id'] = $this->language->get('column_order_id');
    	$data['column_product_name'] = $this->language->get('column_product_name');
		$data['column_unit_price'] = $this->language->get('column_unit_price');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_commission'] = $this->language->get('column_commission');
		$data['column_amount'] = $this->language->get('column_amount');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_transaction_status'] = $this->language->get('column_transaction_status');
		$data['column_paid_status'] = $this->language->get('column_paid_status');
		$data['column_vendor_name'] = $this->language->get('column_vendor_name');
		$data['column_payment_amount'] = $this->language->get('column_payment_amount');
		$data['column_payment_type'] = $this->language->get('column_payment_type');
		$data['column_payment_date'] = $this->language->get('column_payment_date');
		$data['column_order_product'] = $this->language->get('column_order_product');
		
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_status'] = $this->language->get('entry_status');
		
		$data['button_Paypal'] = $this->language->get('button_Paypal');
		$data['button_addPayment'] = $this->language->get('button_addPayment');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['config_currency'] = $this->config->get('config_currency');

		$data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_vendor_group'])) {
			$url .= '&filter_vendor_group=' . $this->request->get['filter_vendor_group'];
		}
		
		if (isset($this->request->get['filter_paid_status'])) {
			$url .= '&filter_paid_status=' . $this->request->get['filter_paid_status'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;	
		$data['filter_vendor_group'] = $filter_vendor_group;	
		$data['filter_paid_status'] = $filter_paid_status;		
		$data['filter_order_status_id'] = $filter_order_status_id;
		
		$data['addPayment'] = $this->url->link('report/mvd_vendor_transaction/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/mvd_vendor_transaction', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_total - $this->config->get('config_limit_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_total, ceil($order_total / $this->config->get('config_limit_admin')));
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/mvd_vendor_transaction.tpl', $data));
	}
	
	public function insert() {
    	$this->load->language('report/mvd_vendor_transaction');

    	$this->document->setTitle($this->language->get('heading_title')); 
		
		$this->load->model('report/mvd_vendor_transaction');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			if (isset($this->request->get['filter_date_start'])) {
				$filter_date_start = $this->request->get['filter_date_start'];
			} else {
				$filter_date_start = '';
			}

			if (isset($this->request->get['filter_date_end'])) {
				$filter_date_end = $this->request->get['filter_date_end'];
			} else {
				$filter_date_end = '';
			}
			
			if (isset($this->request->get['filter_vendor_group'])) {
				$filter_vendor_group = $this->request->get['filter_vendor_group'];
			} else {
				$filter_vendor_group = 0;
			}
			
			if (isset($this->request->get['filter_paid_status'])) {
				$filter_paid_status = $this->request->get['filter_paid_status'];
			} else {
				$filter_paid_status = 0;
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$filter_order_status_id = $this->request->get['filter_order_status_id'];
			} else {
				$filter_order_status_id = 0;
			}	
					
			$url = '';
							
			if (isset($this->request->get['filter_date_start'])) {
				$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
			}
			
			if (isset($this->request->get['filter_date_end'])) {
				$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
			}
			
			if (isset($this->request->get['filter_vendor_group'])) {
				$url .= '&filter_vendor_group=' . $this->request->get['filter_vendor_group'];
			}
			
			if (isset($this->request->get['filter_paid_status'])) {
				$url .= '&filter_paid_status=' . $this->request->get['filter_paid_status'];
			}
			
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
				
			$this->load->model('report/mvd_vendor_transaction');
			
			$orders = array();
			$store_revenue = array();
			
			$data = array(
				'filter_date_start'	     => $filter_date_start, 
				'filter_date_end'	     => $filter_date_end, 
				'filter_vendor_group'    => $filter_vendor_group,
				'filter_paid_status'     => $filter_paid_status,
				'filter_order_status_id' => $filter_order_status_id
			);
			
			$results = $this->model_report_mvd_vendor_transaction->getVendorOrders($data);
			
			$store_revenues = $this->model_report_mvd_vendor_transaction->getVendorTotalAmount($data);
			
			$shipping_charges = $this->model_report_mvd_vendor_transaction->getShippingChargedTotal($data);
		
			$shipping_charged = 0;
			foreach ($shipping_charges AS $shipping_charge) {
				$shipping_charged = $shipping_charge['shipping_charged'];
				$shipping_tax = $shipping_charge['shipping_tax'];
			}
			
			$discount_coupons = $this->model_report_mvd_vendor_transaction->getCouponTotal($data);
			$coupon_amount = 0;
			foreach ($discount_coupons AS $discount_coupon) {
				$coupon_amount = $discount_coupon['discount_amount'];
				$coupon_tax = $discount_coupon['discount_tax'];
			}
				
			foreach ($results as $result) {
				$orders[] = array(
					'product_id'	=> $result['product_id'],
					'order_id'  	=> $result['order_id'],
					'product_name'  => $result['product_name'],
					'paid_status'   => $result['paid_status']
				);
			}
			
			$payment_option = $this->request->get['payment_option'];
			$chequeno = isset($this->request->get['chequeno']) ? $this->request->get['chequeno'] : '';
			$opm = isset($this->request->get['opm']) ? $this->request->get['opm'] : '';
			
			switch ($payment_option) {
				case 'paypal_standard':
				$payment_type = 'Paypal Standard';
				break;
				
				case 'pay_cheque':
				$payment_type = 'Pay Cheque' . ' - No. : ' . $chequeno;
				break;
				
				case 'other_payment_method':
				$payment_type = 'Other Payment Method' . ' - ' . $opm;
				break;
			}
			
			foreach ($store_revenues AS $store) {
				$store_revenue[] = array (
					'vendor_id'			=> $store['vendor_id'],
					'payment_type'		=> $payment_type,
					'paid_amount'		=> $store['vendor_amount'] + $shipping_charged - $coupon_amount + ($this->config->get('tax_status') ? ($shipping_tax + $store['total_vendor_tax'] - $coupon_tax): 0)
				);
			}
		
			$this->model_report_mvd_vendor_transaction->addPaymentToVendor($store_revenue,serialize($orders));
			
			$this->response->redirect($this->url->link('report/mvd_vendor_transaction', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}
	
    	$this->response->setOutput($this->render());
  	}
	
	public function removeHistory() {
		$this->language->load('report/mvd_vendor_transaction');

		$this->load->model('report/mvd_vendor_transaction');

		$json = array();

		if (!$this->user->hasPermission('modify', 'report/mvd_vendor_transaction')) {
      		$json['error'] = $this->language->get('error_permission');
    	} else {
		
			if (isset($this->request->get['payment_id'])) {
				$this->model_report_mvd_vendor_transaction->removeHistory($this->request->get['payment_id']);
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->setOutput(json_encode($json));
  	}
	
	public function addPaymentRecord() {
		
		$this->language->load('report/mvd_vendor_transaction');
		$this->load->model('report/mvd_vendor_transaction');
		
		$order_detail = array();
		$order_id = $this->request->get['oid'];
		$product_id = $this->request->get['pid'];
		$order_product_id = $this->request->get['opid'];
		
		$getOPs = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product op WHERE op.order_id = '" . (int)$order_id . "' AND op.product_id = '" . (int)$product_id . "' AND op.order_product_id = '" . (int)$order_product_id . "' AND op.vendor_paid_status = '0'");
				
		if ($getOPs->row) {
			$order_detail[] = array(
				'product_id'	=> $getOPs->row['product_id'],
				'order_id'  	=> $getOPs->row['order_id'],
				'product_name'  => $getOPs->row['name']
			);
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_payment SET vendor_id = '" . (int)$getOPs->row['vendor_id'] . "', payment_info = '" . serialize($order_detail) . "', payment_amount = '" . (float)($getOPs->row['vendor_total']+($this->config->get('tax_status') ? $getOPs->row['vendor_tax']: 0)) . "', payment_type = '" . $this->language->get('text_paypal_standard') ."', payment_status = '5', payment_date = Now()");
			$this->db->query("UPDATE " . DB_PREFIX . "order_product op SET vendor_paid_status = '1' WHERE op.order_id = '" . (int)$getOPs->row['order_id'] . "' AND op.product_id = '" . (int)$getOPs->row['product_id'] . "' AND op.order_product_id = '" . (int)$order_product_id . "' AND op.vendor_paid_status = '0'");					
		}
		
		$this->response->redirect($this->url->link('report/mvd_vendor_transaction', 'token=' . $this->session->data['token'], 'SSL'));

		$this->response->setOutput($this->render());
  	}
}
?>