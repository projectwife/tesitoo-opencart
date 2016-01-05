<?php
class ModelLocalisationWeight extends Model {

	public function getWeightDescriptions() {
		$weight_desc_data = $this->cache->get('weight_desc');

		if (!$weight_desc_data) {
			$weight_desc_data = array();

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description ORDER BY weight_class_id ASC");

			foreach ($query->rows as $result) {
				$weight_desc_data[$result['weight_class_id']] = array(
					'weight_class_id'   => $result['weight_class_id'],
					'language_id'       => $result['language_id'],
					'title'             => $result['title'],
					'unit'              => $result['unit'],
				);
			}

			$this->cache->set('weight_desc', $weight_desc_data);
		}

		return $weight_desc_data;
	}
}