<?php
class ModelLocalisationUnits extends Model {

	public function getUnitDescriptions() {
		$unit_desc_data = $this->cache->get('unit_desc');

		if (!$unit_desc_data) {
			$unit_desc_data = array();

			$query = $this->db->query("SELECT ucd.*, uc.sort_order FROM " . DB_PREFIX . "unit_class uc LEFT JOIN " . DB_PREFIX . "unit_class_description ucd ON (uc.unit_class_id = ucd.unit_class_id) ORDER BY uc.sort_order, ucd.unit_class_id ASC");

			foreach ($query->rows as $result) {
				$unit_desc_data[$result['unit_class_id']] = array(
					'unit_class_id'   => $result['unit_class_id'],
					'language_id'     => $result['language_id'],
					'title'           => $result['title'],
					'abbreviation'    => $result['abbreviation'],
					'note'            => $result['note'],
					'sort_order'      => $result['sort_order']
				);
			}

			$this->cache->set('unit_desc', $unit_desc_data);
		}

		return $unit_desc_data;
	}
}
