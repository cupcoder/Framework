<?
	namespace Framework\Router;
	
	class Exemplar {
		private $type, 
				$route,
				$handle,
				
				$before = [],
				$assert = [];
				
		public function __construct($type, $route, $handle) {
			$this->type = strtoupper($type);
			$this->route = $route;
			$this->handle = $handle;
		}
		
		public function before($handle) {
			if (is_array($handle)) {
				$this->before = array_merge($this->before, array_values($handle));
			} else {
				$this->before[] = $handle;
			}
			
			return $this;
		}
		
		public function assert($param, $exp) {
			$this->assert[$param] = $exp;
			
			return $this;
		}
		
		public function exec($method, $uri) {
			if (in_array($this->type, ['ALL', strtoupper($method)]) && ($response = $this->prepare($uri)) !== null) { 
				return $response;
			}
			return null;
		}
		
		private function prepare($uri) {
			$this->route = '#^' . preg_replace_callback('#{([a-zA-Z0-9]+)}#', function($m) {
				return isset($this->assert[$m[1]]) ? '(' . $this->assert[$m[1]] . ')' : '([^\\/]+)';
			}, str_replace('/', '\\/', $this->route)) . '$#';
			if (preg_match($this->route, $uri, $params)) {
				array_shift($params);
				if (count($this->before) > 0) {
					$beforeObject = new \BeforeController();
					foreach ($this->before as $before) {
						if (($return = call_user_func_array([$beforeObject, $before . 'Action'], $params)) !== null) {
							return $return;
						}
					}
				}
				$handle = explode('::', $this->handle);
				if (count($handle) !== 2) {
					throw new \Framework\Exception('Invalid handle: "' .htmlspecialchars($this->handle). '"');
				}
				$controller = '\\' . $handle[0] . 'Controller';
				if (($return = call_user_func_array([new $controller(), $handle[1] . 'Action'], $params)) !== null) {
					return $return;
				}
				throw new \Framework\Exception('Controller return null.');
			} 
			return null;
		}
	}