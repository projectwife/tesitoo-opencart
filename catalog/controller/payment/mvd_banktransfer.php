<?php
class ControllerPaymentMVDBankTransfer extends Controller {
	public function index() {
		$this->load->language('payment/mvd_banktransfer');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_payment'] = $this->language->get('text_payment');

		$data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->load->model('checkout/order');
		$mybankinfo = $this->model_checkout_order->PaymentGateway();
		
		$format = '<b>' . $this->language->get('text_bank_name') . '</b>' . '{bank_name}' . "\n" . '<b>' . $this->language->get('text_iban') . '</b>' . '{iban}' . "\n" . '<b>' . $this->language->get('text_swift_bic') . '</b>' . '{swift_bic}';
		$find = array(
			'{bank_name}',
			'{iban}',
			'{swift_bic}',
		);
								
		$replace = array(
			'bank_name' 	=> $mybankinfo['bank_name'],
			'iban'  		=> $mybankinfo['iban'],
			'swift_bic'   	=> $mybankinfo['swift_bic']
		);
		
		$data['bank'] = nl2br(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))));

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mvd_banktransfer.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/mvd_banktransfer.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/mvd_banktransfer.tpl', $data);
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'mvd_banktransfer') {
			$this->load->language('payment/mvd_banktransfer');

			$this->load->model('checkout/order');
			$mybankinfo = $this->model_checkout_order->PaymentGateway();
		
			$format = '<b>' . $this->language->get('text_bank_name') . '</b>' . '{bank_name}' . "\n" . '<b>' . $this->language->get('text_iban') . '</b>' . '{iban}' . "\n" . '<b>' . $this->language->get('text_swift_bic') . '</b>' . '{swift_bic}';
			$find = array(
				'{bank_name}',
				'{iban}',
				'{swift_bic}',
			);
									
			$replace = array(
				'bank_name' 	=> $mybankinfo['bank_name'],
				'iban'  		=> $mybankinfo['iban'],
				'swift_bic'   	=> $mybankinfo['swift_bic']
			);
			
			$comment  = $this->language->get('text_instruction') . "\n\n";
			$comment .= nl2br(str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))))) . "\n\n";
			$comment .= $this->language->get('text_payment');
			
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
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('mvd_banktransfer_order_status_id'), $comment, true);
		}
	}
}