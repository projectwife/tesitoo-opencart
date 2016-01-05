<?php
class ModelLocalisationLength extends Model {

	public function getLengthDescriptions() {
		$length_desc_data = $this->cache->get('length_desc');

		if (!$length_desc_data) {
			$length_desc_data = array();

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description ORDER BY length_class_id ASC");

			foreach ($query->rows as $result) {
				$length_desc_data[$result['length_class_id']] = array(
					'length_class_id'   => $result['length_class_id'],
					'language_id'       => $result['language_id'],
					'title'             => $result['title'],
					'unit'              => $result['unit'],
				);
			}

			$this->cache->set('length_desc', $length_desc_data);
		}

		return $length_desc_data;
	}
}