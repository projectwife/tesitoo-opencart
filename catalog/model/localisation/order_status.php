<?php
class ModelLocalisationOrderStatus extends Model {

	public function getOrderStatus() {
		$orderStatus_desc_data = $this->cache->get('order_status_desc');

		if (!$order_status_desc_data) {
			$order_status_desc_data = array();

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status ORDER BY order_status_id ASC");

			foreach ($query->rows as $result) {
				$orderStatus_desc_data[$result['order_status_id']] = array(
					'order_status_id'  => $result['order_status_id'],
					'language_id'      => $result['language_id'],
					'name'		   => $result['name'],
				);
			}

			$this->cache->set('order_status_desc', $order_status_desc_data);
		}

		return $order_status_desc_data;
	}
}
