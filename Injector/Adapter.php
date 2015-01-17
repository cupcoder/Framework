<?
	namespace Framework\Injector;
	
	class Adapter {
		static private $_shared;
		private $storage = [];
		
		public function __construct($def = true) {
			if ($def === true) {
				$this->set('request', function() {
					return new \Framework\Request\Adapter();
				});
				$this->set('response', function() {
					return new \Framework\Response\Adapter();
				});
			}
		}
		
		public function has($name) {
			return isset($this->storage[$name]);
		}
		
		public function set($name, $handle) {
			$this->storage[$name] = new Exemplar($name, $handle);
		}
		
		public function get($name) {
			if (!isset($this->storage[$name])){
				throw new \Framework\Exception('Injector Element with key "' . htmlspecialchars($name) . '" not found.');
			}
			
			return $this->storage[$name]->exec();
		}
		
		static public function shared() {
			if (self::$_shared === null) {
				self::$_shared = new self();
			}
			
			return self::$_shared;
		}
	}