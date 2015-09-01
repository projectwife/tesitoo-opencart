<?php

class Override 
{
	protected $collection;

	/**
	 * Get a value by name
	 *
	 * @param $name
	 * @return mixed | null or processed value if set
	 */
	public function get($name)
	{
		if(isset($this->getCollection()[$name])) {
			return $this->processValue($this->getCollection()[$name]);
		} else {
			return $this->processValue($name);
		}
	}

	/**
	 * Set a value in the override collection
	 *
	 * @param $name
	 * @param $value
	 * @return $this
	 */
	public function set($name, $value)
	{
	    $this->getcollection()[$name] = $value;
		return $this;
	}

	/**
	 * Get the collection array
	 *
	 * @return array
	 */
	protected function getCollection()
	{
		if($this->collection == null) {
			$this->collection = array();
		}

		return $this->collection;
	}

	/**
	 * Process the value
	 *
	 * @param $value
	 * @return mixed
	 */
	protected function processValue($value)
	{
	    return $value;
	}
} 