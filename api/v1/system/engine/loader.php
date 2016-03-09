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

	/**
	 * Registers a model in the registry.
	 *
	 * Calls ModelOverride to get the directory
	 *
	 * @param $model
	 */
	public function model($model) {
		if ( $overrides = $this->registry->get('api_override_model') ) {
			$this->modelFromDirectory($model, $overrides->get($model));
		} else {
			$this->loader->model($model);
		}
	}

	public function view($template, $data = array()) {
		if ($this->interceptViewData == true) {
			return $data;
		} else {
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

	/**
	 * Allows loading from a specified directory instead of DIR_APPLICATION
	 *
	 * @param $model
	 * @param $directory
	 * @throws Exception when file was not found
	 */
	private function modelFromDirectory($model, $directory) {
		if ( ! $directory ) {
			$directory = DIR_APPLICATION;
		}

		$file = $directory . '/model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if (file_exists($file)) {
			if (class_exists('VQMod')) {
				include_once(VQMod::modCheck(modification($file), $file));
			} else {
				include_once($file);
			}

			$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {
			throw new Exception('Model not found', 404);
		}
	}
}