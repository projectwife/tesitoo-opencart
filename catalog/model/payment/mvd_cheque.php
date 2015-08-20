<?php
class ModelPaymentMVDCheque extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/mvd_cheque');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('mvd_cheque_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		$this->load->model('checkout/order');
		$mybankinfo = $this->model_checkout_order->PaymentGateway();
		
		if ($this->config->get('mvd_cheque_total') > 0 && $this->config->get('mvd_cheque_total') > $total) {
			$status = false;
		} elseif ((!$this->config->get('mvd_cheque_geo_zone_id')) && ($mybankinfo) && ($mybankinfo['accept_cheques'])) {
			$status = true;
		} elseif (($query->num_rows) && ($mybankinfo) && ($mybankinfo['accept_cheques'])) {
			$status = true;
		} else {
			$status = false;
		}
		
		if ($this->config->get('config_store_id') == 0) {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'mvd_cheque',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('mvd_cheque_sort_order')
			);
		}

		return $method_data;
	}
}