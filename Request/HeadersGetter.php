<?
	namespace Framework\Request;
	
	class HeadersGetter {
		private $link;
		
		public function __construct(&$link) {
			$this->link = &$link;
		}
		
		public function has($key) {
			return isset($this->link[$this->key($key)]);
		}
		
		public function isArray($key) {
			return $this->has($key) && is_array($this->link[$this->key($key)]);
		}
		
		public function get($key) {
			return $this->has($key) && !is_array($this->link[$this->key($key)]) ? $this->link[$this->key($key)] : null;
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
			return $this->has($key) ? $this->link[$this->key($key)] : null;
		}
		
		private function key($key) {
			return "HTTP_" . str_replace('-', '_', strtoupper($key));
		}
	}