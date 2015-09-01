<?php

class OverrideTree extends Override
{

	/**
	 * Directories to search in order of array
	 * @var array
	 */
	protected $searchDirectories = array();

	/**
	 * Base folder to search in
	 *
	 * example: catalog/{model}
	 * @var string
	 */
	protected $base = '';

	/**
	 * Create with an optional set of directories searched by order
	 *
	 * @param string $base
	 * @param array $searchDirectories
	 * @param array $overrides ex: [ 'catalog/product' => DIR_APPLICATION.'../admin' ]
	 */
	public function __construct($base, $searchDirectories = array(), $overrides)
	{	
		$this->base = $base;
	    $this->searchDirectories = array_merge($this->searchDirectories, $searchDirectories);

		if(is_array($overrides)) {
			$this->collection = $overrides;
		}
	}


	/**
	 * @param $value
	 * @return string
	 */
	protected function processValue($value)
	{
		// Return the value if an override was specified
		if(is_file($value)) {
			return $value;
		}

		/**
		 * Search for the file in the directories
		 * Break when found
		 */
		foreach($this->searchDirectories as $dir) {
			$dir = preg_replace('/\/$/', '', $dir); // If a trailing slash is given remove it

			$file = $dir.DIRECTORY_SEPARATOR.$this->base.DIRECTORY_SEPARATOR.$value.'.php';
			if(is_file($file)){
				$value = $dir;
				break;
			}
		}

	    return $value;
	}
} 