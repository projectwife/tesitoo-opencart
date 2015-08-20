<?php
class ModelShippingMVDMFlat extends Model {
	function getQuote($address) {
		$this->load->language('shipping/mvdm_flat');
		$this->load->model('tool/image');
		$method_data = array();
		$valid_courier = array();
		$shipping_couriers = array();		
		$final_shipping_method = array();
		
		if ($this->config->get('mvdm_flat_status')) {
			$validate_shipping_method = TRUE;
			$num_of_shipping_products = 0;

			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$query_shipping_data = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_shipping WHERE product_id = '" . (int)$product['product_id'] . "' ORDER BY product_shipping_id");

					if ($query_shipping_data->rows) {
						foreach ($query_shipping_data->rows as $courier) {
							$valid_destination = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$courier['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
							if ($valid_destination->row) {
								$shipping_couriers[] = array(
									'product_id'	=>	$courier['product_id'],
									'courier_id'	=>	$courier['courier_id'],
									'shipping_rate'	=>	$product['quantity']*$courier['shipping_rate'],
									'weight'		=>	$product['weight'],
									'geo_zone_id'	=>	$courier['geo_zone_id']
								);
							}
						}
					}
					$num_of_shipping_products++;
				}				
			}
			if ($shipping_couriers) {
			foreach ($shipping_couriers as $shipping_courier) {
				$i=0;
				foreach ($shipping_couriers as $shipping_compare) {
					if ($shipping_courier['courier_id'] == $shipping_compare['courier_id']) {						
						$i++;
					}
				}
				
				if ($num_of_shipping_products == $i) {
					$valid_courier[] = array (
						'courier_id'	=> $shipping_courier['courier_id']
					);
				}
			}

			$available_shipping_methods = array_map("unserialize", array_unique(array_map("serialize", $valid_courier)));

			foreach ($available_shipping_methods as $shipping_method) {
				$i=0;
				$total_weight=0;
				$total_shipping=0;
				foreach ($shipping_couriers as $shipping_courier){
					if ($shipping_method['courier_id'] == $shipping_courier['courier_id']) {
						$total_shipping += $shipping_courier['shipping_rate'];
						$total_weight += $shipping_courier['weight'];
						$i++;
						
						if ($num_of_shipping_products == $i) {
							$getCourierData = $this->getCourierName($shipping_courier['courier_id']);
							$final_shipping_method[] = array(
								'courier_name' 		=> $getCourierData['courier_name'],
								'description'		=> $getCourierData['description'],
								'courier_id' 		=> $shipping_courier['courier_id'],
								'courier_image' 	=> $this->model_tool_image->resize($this->getCourierImage($shipping_courier['courier_id']),'88','30'),
								'shipping_weight'	=> $total_weight,
								'shipping_rate' 	=> $total_shipping
							);
						}
					}
				}
			}
			
			if ($final_shipping_method) {
				$quote_data = array();
				
				foreach ($final_shipping_method as $shipping_method) {
					if ($shipping_method['courier_image']) {
						$cimage = '<td><img src=' . $shipping_method['courier_image'] . ' ></td>';
					} else {
						$cimage = false;
					}
				
					$quote_data['mvdm_flat' . $shipping_method['courier_id']] = array(
						'code'         => 'mvdm_flat.mvdm_flat' . $shipping_method['courier_id'],
						'title'        => $shipping_method['courier_name'] . ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($shipping_method['shipping_weight'], $this->config->get('config_weight_class_id')) . ') <br/><span style="font-size:smaller;color:#999">' . $shipping_method['description'] . '</span>',
						'cost'         => $shipping_method['shipping_rate'],
						'cimage'	   => $cimage,
						'tax_class_id' => $this->config->get('mvdm_flat_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($shipping_method['shipping_rate'], $this->config->get('mvdm_flat_tax_class_id'), $this->config->get('config_tax')))
					);
					$i++;
				}
			
				$method_data = array(
					'code'       => 'mvdm_flat',
					'title'      => $this->language->get('text_title'),
					'quote'      => $quote_data,
					'sort_order' => $this->config->get('mvflat_sort_order'),
					'error'      => false
				);
			}
			}
		}
	
		return $method_data;
	}
	
	Private function getCourierName($courier_id) {
		$query = $this->db->query("SELECT courier_name,description FROM " . DB_PREFIX . "courier WHERE courier_id = '" . (int)$courier_id . "'");
		return $query->row;	
	}
	
	Private function getCourierImage($courier_id) {
		$query = $this->db->query("SELECT courier_image FROM " . DB_PREFIX . "courier WHERE courier_id = '" . (int)$courier_id . "'");
		return $query->row['courier_image'];	
	}
}
?>