<?
	namespace Framework;
	
	class Config {
		private $storage;
		
		public function __construct($data) {
			$this->storage = $data;
		}
		
		public function toArray() {
			return $this->storage;
		}
		
		public function __get($key) {
			if (!isset($this->storage[$key])) return null;
			if (is_array($this->storage[$key])) {
				return new self($this->storage[$key]);
			} else {
				return $this->storage[$key];
			}
		}
		
	}