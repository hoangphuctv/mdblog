<?php

class Sample {

	function __construct($data=[]) {
		if ($data) {
			foreach ($data as $key =>$value) {
				$this->$key = $value;
			}
		}
	}

	public function __get($key) {
		return $this->get($key);
	}

	public function get($key, $default=null) {
		if (empty($key)) {
			return $default;
		}

		if (!isset($this->$key)) {
			return $default;
		}
		$value = $this->$key;
		if (is_array($this->$key) && !is_object($this->$key)) {
			return new self($value);
		}
		return $value;
	}

	public function set($key, $value) {
		$this->$key = $value;
	}
}
