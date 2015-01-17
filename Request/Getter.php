<?
	namespace Framework\Request;
	
	class Getter {
		private $link;
		
		public function __construct(&$link) {
			$this->link = &$link;
		}
		
		public function has($key) {
			return isset($this->link[$key]);
		}
		
		public function isArray($key) {
			return $this->has($key) && is_array($this->link[$key]);
		}
		
		public function get($key) {
			return $this->has($key) && !is_array($this->link[$key]) ? $this->link[$key] : null;
		}
		
		public function getTrimmed($key) {
			return trim($this->get($key));
		}
		
		public function getInt($key) {
			return intval($this->get($key));
		}
		
		public function getFloat($key) {
			return floatval($this->get($key));
		}
		
		public function getRaw($key) {
			return $this->has($key) ? $this->link[$key] : null;
		}
	}