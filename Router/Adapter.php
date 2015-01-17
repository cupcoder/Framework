<?
	namespace Framework\Router;
	
	class Adapter {
		static private $_shared;

		private $storage = [];
		
		public function add($type, $route, $handle) {
			$this->storage[] = $exemplar = new Exemplar($type, $route, $handle);
			return $exemplar;
		}

		public function register($path) {
			spl_autoload_register(function($class) use ($path) {
				if (is_file($path . '/' . $class . '.php')) {
					require_once $path . '/' . $class . '.php';
				}
			});
		}
		
		public function exec($method, $uri) {
			foreach ($this->storage as $route) {
				if (($response = $route->exec($method, $uri)) !== null) {
					return $response;
				}
			}
			
			throw new \Framework\Exception('No route found.', 404);
		}
		
		static public function shared() {
			if (self::$_shared === null) {
				self::$_shared = new self();
			}
			return self::$_shared;
		}
	}