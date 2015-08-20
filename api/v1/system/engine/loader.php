<?php
final class ApiLoader {
	private $registry;
	private $loader;
	private $interceptViewData;

	public function __construct($registry) {
		$this->registry = $registry;
		$this->loader = new Loader($registry);

		$this->interceptViewData = true;
	}

	public function controller($route, $args = array()) {
		return $this->loader->controller($route, $args);
	}

	public function model($model) {
		$this->loader->model($model);
	}

	public function view($template, $data = array()) {
		if($this->interceptViewData == true) {
			return $data;
		}
		else {
			return $this->loader->view($template, $data);
		}
	}

	public function library($library) {
		$this->loader->library($library);
	}

	public function helper($helper) {
		$this->loader->helper($helper);
	}

	public function config($config) {
		$this->loader->config($config);
	}

	public function language($language) {
		return $this->loader->language($language);
	}

	public function setInterceptViewData($interceptViewData) {
		$this->interceptViewData = $interceptViewData;
	}
}