<?php
class ModelLocalisationUnits extends Model {

	public function getUnitDescriptions() {
		$unit_desc_data = $this->cache->get('unit_desc');

		if (!$unit_desc_data) {
			$unit_desc_data = array();

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "unit_class_description ORDER BY unit_class_id ASC");

			foreach ($query->rows as $result) {
				$unit_desc_data[$result['unit_class_id']] = array(
					'unit_class_id'   => $result['unit_class_id'],
					'language_id'     => $result['language_id'],
					'title'           => $result['title'],
					'abbreviation'    => $result['abbreviation'],
					'note'            => $result['note']
				);
			}

			$this->cache->set('unit_desc', $unit_desc_data);
		}

		return $unit_desc_data;
	}
}
