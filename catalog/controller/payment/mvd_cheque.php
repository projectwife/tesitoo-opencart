<?php
class ControllerPaymentMVDCheque extends Controller {
	public function index() {
		$this->load->language('payment/mvd_cheque');
		
		$this->load->model('checkout/order');
		$mybankinfo = $this->model_checkout_order->PaymentGateway();
		
		$format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {postcode}' . "\n" . '{zone}, {country}';
		$find = array(
			'{company}',
			'{address_1}',
			'{address_2}',
			'{city}',
			'{postcode}',
			'{zone}',
			'{country}'
		);
		
		$this->load->model('localisation/zone');
		$zone = $this->model_localisation_zone->getZone((int)$mybankinfo['zone_id']);
		
		$this->load->model('localisation/country');
		$country = $this->model_localisation_country->getCountry((int)$mybankinfo['country_id']);
		
		$replace = array(
			'company'   => $mybankinfo['company'],
			'address_1' => $mybankinfo['address_1'],
			'address_2' => $mybankinfo['address_2'],
			'city'      => $mybankinfo['city'],
			'postcode'  => $mybankinfo['postcode'],
			'zone' 		=> isset($zone['name']) ? $zone['name'] : '',
			'country'   => $country['name']
		);

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_payable'] = $this->language->get('text_payable');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_payment'] = $this->language->get('text_payment');

		$data['button_confirm'] = $this->language->get('button_confirm');
		
		$data['payable'] = $mybankinfo['firstname'] . ' ' . $mybankinfo['lastname'];
		$data['address'] = nl2br(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))));

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mvd_cheque.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/mvd_cheque.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/mvd_cheque.tpl', $data);
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'mvd_cheque') {
			$this->load->language('payment/mvd_cheque');
			
			$this->load->model('checkout/order');
			$mybankinfo = $this->model_checkout_order->PaymentGateway();
			
			$format = '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city}, {postcode}' . "\n" . '{zone}, {country}';
			$find = array(
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{country}'
			);
			
			$this->load->model('localisation/zone');
			$zone = $this->model_localisation_zone->getZone((int)$mybankinfo['zone_id']);
			
			$this->load->model('localisation/country');
			$country = $this->model_localisation_country->getCountry((int)$mybankinfo['country_id']);
			
			$replace = array(
				'company'   => $mybankinfo['company'],
				'address_1' => $mybankinfo['address_1'],
				'address_2' => $mybankinfo['address_2'],
				'city'      => $mybankinfo['city'],
				'postcode'  => $mybankinfo['postcode'],
				'zone' 		=> isset($zone['name']) ? $zone['name'] : '',
				'country'   => $country['name']
			);
			
			$comment  = $this->language->get('text_payable') . "\n";
			$comment .= $mybankinfo['firstname'] . ' ' . $mybankinfo['lastname'] . "\n\n";
			$comment .= $this->language->get('text_address') . "\n";
			$comment .= nl2br(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))))) . "\n\n";
			$comment .= $this->language->get('text_payment') . "\n";
			
			$totalOPs = $this->db->query("SELECT SUM(vendor_total+vendor_tax) as total, vendor_id FROM " . DB_PREFIX . "order_product op WHERE op.order_id = '" . (int)$this->session->data['order_id'] . "' AND op.vendor_paid_status = '0' GROUP BY op.vendor_id");
			$getOPs = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product op WHERE op.order_id = '" . (int)$this->session->data['order_id'] . "' AND op.vendor_paid_status = '0'");
			$getOSs = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_shipping os WHERE os.order_id = '" . (int)$this->session->data['order_id'] . "' AND os.shipping_paid_status = '0'");
			$order_detail = array();			
			
			if ($totalOPs->rows) {
				foreach ($totalOPs->rows AS $pay_to_vendor) {
					foreach($getOPs->rows as $op) {
						if ($pay_to_vendor['vendor_id'] == $op['vendor_id']) {
							$order_detail[] = array(
							'product_id'	=> $op['product_id'],
							'order_id'  	=> $op['order_id'],
							'product_name'  => $op['name']
							);
						}
					}							
								
					if ($getOSs->rows) {
						$ship_cost = 0;
						foreach ($getOSs->rows as $shipping_cost) {
							if ($pay_to_vendor['vendor_id'] == $shipping_cost['vendor_id']) {
								$ship_cost = $shipping_cost['cost']+$shipping_cost['tax'];
							}
						}							
									
						$order_product_plus_shipping = $pay_to_vendor['total']+$ship_cost;
						$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_payment SET vendor_id = '" . (int)$pay_to_vendor['vendor_id'] . "', payment_info = '" . serialize($order_detail) . "', payment_type = '" . $this->language->get('text_title') . "', payment_amount = '" . (float)$order_product_plus_shipping . "', payment_status = '5', payment_date = Now()");
									
						foreach ($getOSs->rows AS $pay_shipping) {
							if ($pay_shipping['vendor_id'] == $pay_to_vendor['vendor_id']) {
								$this->db->query("UPDATE " . DB_PREFIX . "order_product op SET vendor_paid_status = '1' WHERE op.order_id = '" . (int)$this->session->data['order_id'] . "' AND op.vendor_paid_status = '0'");
								$this->db->query("UPDATE " . DB_PREFIX . "order_shipping os SET shipping_paid_status = '1' WHERE os.order_id = '" . (int)$this->session->data['order_id'] . "' AND os.shipping_paid_status = '0'");
							}
						}
					} else {
						$this->db->query("INSERT INTO " . DB_PREFIX . "vendor_payment SET vendor_id = '" . (int)$pay_to_vendor['vendor_id'] . "', payment_info = '" . serialize($order_detail) . "', payment_type = '" . $this->language->get('text_title') . "', payment_amount = '" . (float)$pay_to_vendor['total'] . "', payment_status = '5', payment_date = Now()");
						$this->db->query("UPDATE " . DB_PREFIX . "order_product op SET vendor_paid_status = '1' WHERE op.order_id = '" . (int)$this->session->data['order_id'] . "' AND op.vendor_paid_status = '0'");
					}							
					unset($order_detail);
				}						
			}

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('mvd_cheque_order_status_id'), $comment, true);
		}
	}
}