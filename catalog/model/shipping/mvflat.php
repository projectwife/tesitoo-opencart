<?php
class ModelShippingMVFlat extends Model {
	function getQuote($address) {
		$this->load->language('shipping/mvflat');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('mvflat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if (!$this->config->get('mvflat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
				
		$method_data = array();
		if ($status) {
			$shipping_total = 0;
			$total_weight = 0;
			$validate_shipping_method = TRUE;
			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$vproduct = $this->db->query("SELECT v.prefered_shipping, v.shipping_cost, p.weight FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "vendor v ON (p.product_id = v.vproduct_id) WHERE p.product_id = '" . (int)$product['product_id'] . "'");
						if ($this->config->get('mvflat_shipping_method') == $vproduct->row['prefered_shipping']) {
							$shipping_total += (float)$vproduct->row['shipping_cost'] * $product['quantity'];
							$total_weight += (float)$vproduct->row['weight'] * $product['quantity'];
						} else {
							$validate_shipping_method = FALSE;
						}
				}				
			}
			
			if ($validate_shipping_method) {
				$quote_data = array();
				
				$quote_data['mvflat'] = array(
					'code'         => 'mvflat.mvflat',
					'title'        => $this->language->get('text_description') . ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($total_weight, $this->config->get('config_weight_class_id')) . ')',
					'cost'         => $shipping_total,
					'tax_class_id' => $this->config->get('mvflat_tax_class_id'),
					'text'         => $this->currency->format($this->tax->calculate($shipping_total, $this->config->get('mvflat_tax_class_id'), $this->config->get('config_tax')))
				);

				$method_data = array(
					'code'       => 'mvflat',
					'title'      => $this->language->get('text_title'),
					'quote'      => $quote_data,
					'sort_order' => $this->config->get('mvflat_sort_order'),
					'error'      => false
				);
			}
		}
	
		return $method_data;
	}
}
?>