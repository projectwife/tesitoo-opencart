<?php
class ApiUrl extends Url {

	private $catalogLinks = array();
	
	public function link($route, $args = '', $connection = 'NONSSL') {
		if(in_array($route, $this->catalogLinks)) {
			return $this->catalogLink($route, $args, $connection);
		}
		else {
			return $route;
		}
	}

	public function catalogLink($route, $args = '', $connection = 'NONSSL') {
		return parent::link($route, $args, $connection);
	}

	public function addCatalogLink($route) {
		$this->catalogLinks[] = $route;
	}

	public static function getUrlParams($urlString) {
		parse_str(parse_url(html_entity_decode($urlString), PHP_URL_QUERY), $params);

		return $params;
	}
}
?>